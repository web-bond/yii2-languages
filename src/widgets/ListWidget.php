<?php

namespace klisl\languages\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class ListWidget
 * @package klisl\languages\widgets
 */
class ListWidget extends Widget{

    /** @var array */
    public $array_languages;


    /** @return void */
    public function init() {

        parent::init();

        $language = Yii::$app->language; //текущий язык

        //Создаем массив ссылок всех языков с соответствующими GET параметрами
        $array_lang = [];
        
        $module = Yii::$app->getModule('languages');
        $default_language = $module->default_language;
        $curentLang = Yii::$app->language;
        
        foreach ($module->languages as $key => $value){
            $url = $this->update_url($value, $default_language, $curentLang);
            $link = $this->createLink_2($key, $value, $url);

//            $link = $this->createLink($key, $value);
            $array_lang += [$value => $link];
        }

        //ссылку на текущий язык не выводим
        if(isset($array_lang[$language])) unset($array_lang[$language]);
        $this->array_languages = $array_lang;

    }

    /**
     * @param string $key
     * @param string $value
     * @return string
     */
    protected function createLink($key, $value){
        return Html::a($key, ['languages/default/index', 'lang' => $value], ['class' => 'language '.$value] );
    }
    
    
    protected function createLink_2($key, $value, $link){
        return Html::a($key, $link, ['class' => 'language '.$value] );
    }
    protected function update_url($lang, $default_lang, $current_lang) {
            $_lang_url = Yii::$app->request->url; //полный URL
            $url = $_lang_url;
            $url_list = explode('/', $_lang_url);
            $lang_url = isset($url_list[1]) ? $url_list[1] : null;

            $module = Yii::$app->getModule('languages');
            foreach ($module->languages as $key => $value){
                $url_list_new = $url_list;
                if ($lang_url && $lang_url == $value) {
                    unset($url_list_new[1]);
                    $url = implode('/', $url_list_new);
                }
            }

            if ($lang != $default_lang) {
                $url = '/'.$lang.$url;
            }
            if ($url && $url !== '/') {
                $url = rtrim($url, '/\\');
            }
            if (!$url) {
                $url = '/';
            }


        return $url;
    }


    /**
     * @return string
     */
    public function run() {

        return $this->render('list',[
            'array_lang' => $this->array_languages
        ]);
    }

}
