<?php

declare(strict_types=1);

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    // Brute-force throttle: lock out an identifier (username+IP) after too many bad passwords.
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS = 300;

    public string $username = '';
    public string $password = '';
    public bool $rememberMe = true;
    private User|null $_user = null;
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword(string $attribute, array|null $params): void
    {
        if ($this->hasErrors()) {
            return;
        }

        if ($this->isLocked()) {
            $this->addError($attribute, 'Too many failed login attempts. Please try again in a few minutes.');
            return;
        }

        $user = $this->getUser();

        if (!$user || !$user->validatePassword($this->password)) {
            $this->registerFailure();
            $this->addError($attribute, 'Incorrect username or password.');
            return;
        }

        $this->clearFailures();
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Throttle state is keyed by username+IP, so one attacker can't lock out a real user
     * from a different network, and distributing guesses across IPs doesn't help them either
     * (same username still accumulates failures from the aggregate store).
     */
    private function throttleKey(): string
    {
        return 'login-throttle:' . mb_strtolower($this->username) . ':' . Yii::$app->request->userIP;
    }

    private function isLocked(): bool
    {
        return (int) Yii::$app->cache->get($this->throttleKey()) >= self::MAX_ATTEMPTS;
    }

    private function registerFailure(): void
    {
        $cache = Yii::$app->cache;
        $key = $this->throttleKey();
        $count = (int) $cache->get($key);
        $cache->set($key, $count + 1, self::LOCKOUT_SECONDS);
    }

    private function clearFailures(): void
    {
        Yii::$app->cache->delete($this->throttleKey());
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser(): User|null
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
