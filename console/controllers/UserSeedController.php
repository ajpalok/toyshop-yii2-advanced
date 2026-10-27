<?php

declare(strict_types=1);

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Seeds the ToyShop system administrator account.
 * Safe to run repeatedly: it updates the existing user instead of duplicating it.
 */
class UserSeedController extends Controller
{
    private const ADMIN_EMAIL = 'ajpalok@2haas.com';
    private const ADMIN_USERNAME = 'ajpalok';

    /**
     * php yii user-seed/admin
     * php yii user-seed/admin "MyOwnPassword123"
     *
     * Grants both the "admin" role (backend panel access) and the "customer" role
     * (frontend account/order section), so this one account can be used to exercise both.
     * Requires `php yii rbac/init` to have been run first.
     */
    public function actionAdmin(string $password = ''): int
    {
        if (YII_ENV_PROD) {
            $this->stderr("Refusing to seed a known admin password in production.\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $generated = $password === '';
        if ($generated) {
            $password = Yii::$app->security->generateRandomString(14);
        }

        $user = User::findOne(['email' => self::ADMIN_EMAIL]) ?? new User();
        $user->username = $user->username ?: self::ADMIN_USERNAME;
        $user->email = self::ADMIN_EMAIL;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($password);

        if (!$user->auth_key) {
            $user->generateAuthKey();
        }
        if (!$user->verification_token) {
            $user->generateEmailVerificationToken();
        }

        if (!$user->save()) {
            $this->stderr("Could not save user: " . json_encode($user->errors) . "\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $auth = Yii::$app->authManager;
        foreach (['admin', 'customer'] as $roleName) {
            $role = $auth->getRole($roleName);
            if ($role === null) {
                $this->stderr("Role \"$roleName\" not found - run `php yii rbac/init` first.\n", Console::FG_RED);
                continue;
            }
            if ($auth->getAssignment($roleName, $user->id) === null) {
                $auth->assign($role, $user->id);
            }
        }

        $this->stdout("Admin user ready: {$user->email} (username: {$user->username})\n", Console::FG_GREEN);
        if ($generated) {
            $this->stdout("Generated password: $password\n", Console::FG_YELLOW);
            $this->stdout("Store it now - it is not saved anywhere and cannot be shown again.\n");
        }

        return ExitCode::OK;
    }
}
