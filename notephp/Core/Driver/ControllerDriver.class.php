<?php
/*
 * Editer hebarguan in 2016-1-29
 * Email : hebarguan@hotmail.com
 * 控制器驱动将检测控制器和动作是否存在
 * 并实例运行控制器类
 */
class ControllerDriver {
    public static function work($ModuleName, $ControllerName, $ActionName) {
        // 模块名
        $ModuleName  = ucfirst($ModuleName) ;
        // 控制器名
        $ControllerFileName = ucfirst(strtolower($ControllerName))."Controller";
        // 操作方法名
        $ActionName = $ActionName;
        // 模块目录
        $modulePath = PRO_PATH."/".$ModuleName;
        if( is_dir($modulePath) ) {
            // 检查是否默认控制与动作
            if($ControllerName == C("DEFAULT_INDEX") AND $ActionName == C("DEFAULT_HANDLE")) {
                if( !is_file($defaultControllerFile=$modulePath."/Controller/".$ControllerFileName.EXTS) ) {
                    // 加载欢迎界面
                    Welcome::outputWelcomePage($ModuleName, $ControllerName, $ActionName);
                    exit;
                }
            }
            // 实例化控制器
            $ControllerHandle = new $ControllerFileName();
            // 获取控制器所有操作方法
            $allMethod = get_class_methods($ControllerHandle);
            // 检测是否开启路由大小写
            // 与匹配的方式检测，方便大小写规则
            $ActionPattern = C("URL_CASE_INSENSITIVE") ? "/{$ActionName}/" : "/{$ActionName}/i";
            if( !preg_match($ActionPattern ,join(" ",$allMethod)) ) {
                // 不存在操作方法，放回错误
                trigger_error("不存在动作{$ActionName}",E_USER_ERROR);
            }else{
                // 否则运行操作方法
                call_user_func(array($ControllerHandle ,$ActionName));
            }
        }else{
            trigger_error("不存在模块{$ModuleName}",E_USER_ERROR);   
        }
    }
}