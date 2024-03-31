<?php

namespace controller\app\service\classes;

use controller\app\service\interfaces\ItemInterface;

class ItemService implements ItemInterface
{
    /**
     * @param array $array
     * @return array
     */
    public function formatArray(array $array): array
    {
        return array_map('trim', $array);
    }

    /**
     * @param array $array
     * @return array
     */
    public function isEmptyField(array $array): array
    {
        $errors = [];
        foreach ($array as $key => $value) {
            if (empty($value)) {
                $errors[$key] = 'Veuillez remplir le champ : ' . $key;
            }
            if (in_array($key, ['phone', 'departement', 'categorie', 'price']) && !is_numeric($value)) {
                $errors[$key] = 'Ce champ n\'accepte que les caractères alphanumériques : ' . $key;
            }
            if ($key == 'email' && !$this->isValidEmail($value)) {
                $errors[$key] = 'Please enter a valid email address';
            }
        }
        return array_values(array_filter($errors));
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isValidEmail(string $email): bool
    {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}