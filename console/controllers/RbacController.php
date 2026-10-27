<?php

declare(strict_types=1);

namespace console\controllers;

use common\models\User;
use common\rbac\OrderOwnerRule;
use common\rbac\PendingOrderOwnerRule;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\rbac\Item;

class RbacController extends Controller
{
    /**
     * Creates (or refreshes) the roles and permissions ToyShop needs.
     * Safe to run repeatedly: existing items are reused, not duplicated.
     */
    public function actionInit(): int
    {
        $auth = Yii::$app->authManager;

        $ownerRule = $auth->getRule('isOrderOwner') ?? new OrderOwnerRule();
        $pendingRule = $auth->getRule('isPendingOrderOwner') ?? new PendingOrderOwnerRule();
        foreach ([$ownerRule, $pendingRule] as $rule) {
            if ($auth->getRule($rule->name) === null) {
                $auth->add($rule);
            }
        }

        $viewOrder = $this->permission('viewOrder');
        $viewOwnOrder = $this->permission('viewOwnOrder', $ownerRule->name);
        $cancelOrder = $this->permission('cancelOrder');
        $cancelOwnOrder = $this->permission('cancelOwnOrder', $pendingRule->name);
        $accessBackend = $this->permission('accessBackend');
        $manageOrders = $this->permission('manageOrders');
        $manageCatalog = $this->permission('manageCatalog');
        $manageUsers = $this->permission('manageUsers');

        $this->child($viewOwnOrder, $viewOrder);
        $this->child($cancelOwnOrder, $cancelOrder);

        $this->role('customer', [$viewOwnOrder, $cancelOwnOrder]);
        $this->role('staff', [$accessBackend, $viewOrder, $manageOrders]);
        $staff = $auth->getRole('staff');
        $this->role('admin', [$staff, $manageCatalog, $manageUsers, $cancelOrder]);

        $this->stdout("RBAC ready.\n");

        return ExitCode::OK;
    }

    /**
     * php yii rbac/assign admin you@example.com
     */
    public function actionAssign(string $roleName, string $email): int
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        $user = User::findOne(['email' => $email]);

        if ($role === null || $user === null) {
            $this->stderr("Role or user not found.\n");
            return ExitCode::DATAERR;
        }

        if ($auth->getAssignment($roleName, $user->id) === null) {
            $auth->assign($role, $user->id);
        }

        $this->stdout("Done.\n");

        return ExitCode::OK;
    }

    private function permission(string $name, ?string $ruleName = null): Item
    {
        $auth = Yii::$app->authManager;
        if ($item = $auth->getPermission($name)) {
            return $item;
        }
        $item = $auth->createPermission($name);
        $item->ruleName = $ruleName;
        $auth->add($item);

        return $item;
    }

    private function role(string $name, array $children): Item
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);
        if ($role === null) {
            $role = $auth->createRole($name);
            $auth->add($role);
        }
        foreach ($children as $child) {
            $this->child($role, $child);
        }

        return $role;
    }

    private function child(Item $parent, Item $child): void
    {
        $auth = Yii::$app->authManager;
        if (!$auth->hasChild($parent, $child)) {
            $auth->addChild($parent, $child);
        }
    }
}
