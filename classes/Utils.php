<?php

class Utils {
    function __construct(){
        $this->languages = [
            "html" => [
                "name" => "HTML",
                "icon" => "fab fa-html5"
            ],
            "css" => [
                "name" => "CSS", 
                "icon" => "fab fa-css3"
            ],
            "js" => [
                "name" => "Javascript", 
                "icon" => "fab fa-js"
            ],
            "lua" => [
                "name" => "Lua", 
                "icon" => null
            ]
        ];
    }

    public function reformatLanguage(string $char, string $param){
        if (!isset($this->languages[$char][$param])) return "No";
        return $this->languages[$char][$param];
    }

    public function toBooleanString(string $val){
        return $val == "1" ? "true" : "false";
    }

    public function replaceLinkByTag(string $char){
        $linkByTags = "@((https?://)?([-\w]+\.[-\w\.]+)+\w?(/([-\w/_\.]*(\?\S+)?)?)*)@";
        $regex = preg_replace($linkByTags, "<a href='$1'>$3</a>", $char);
        return $regex;
    }
}