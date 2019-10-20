<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 05.09.2019
 * Time: 21:41
 */

class Validator
{
    public static $template_rules = array(
        'email' => '/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/',
        'login' => '/^[a-z0-9_-]{3,16}$/',
        'password' => '/^[a-z0-9_-]{6,18}$/',
        'any_number' => '[0-9]+'
    );

    public static $template_warnings = array(
        'default' => 'Пожалуйста, проверьте введенные данные.',
        'email' => 'Неверный формат электронной почты.',
        'login' => 'Логин должен состоять из латинских букв и цифр (от 3 до 16 символов).',
        'password' => 'Пароль должен состоять из латинских букв и цифр (от 6 до 18 символов).',
        'any_number' => ''
    );

    public static function validate_data($rules)
    {
        $template_rules = self::$template_rules;
        $template_warnings = self::$template_warnings;
        $validity_result = true;
        $warnings_list = array();

        foreach ($rules as $string => $rule) {
            // Проверка, существует ли такое правило в шаблонах
            if (array_key_exists($rule, $template_rules) === true) {
                $template_rule_exist = true;
                $template_rule = $template_rules[$rule];
                $result = preg_match("$template_rule", "$string");
            } else {
                $template_rule_exist = false;
                $result = preg_match("$rule", "$string");
            }

            if ($result === 1) {
            } else {
                $validity_result = false;
                if($template_rule_exist === true){
                    $warnings_list[$rule] = $template_warnings[$rule];
                } else{
                    $warnings_list['default']=$template_warnings['default'];
                }
            }
        }
        $result_array = ['validity_result' => $validity_result, 'warnings_list' => $warnings_list];
        return $result_array;
    }
}