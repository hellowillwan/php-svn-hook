<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'BasePreCommitCheck.class.php';

class SyntaxCheck extends BasePreCommitCheck {
  
  function getTitle(){
    return "Reject PHP Syntax Error";
  }
  
  public function renderErrorSummary(){
    return "Commit file with PHP Syntax Error";
  }
  
  public function checkFullFile($lines, $filename) {
    $contents = implode("\r\n",$lines);
    $tmp_file_name = 'd:\php-svn-hook-master\tmp\\' . rand() . '_' . preg_replace('/\/|\\\/','_',$filename);
    $ret = file_put_contents($tmp_file_name,$contents);
    exec("php -l $tmp_file_name 2>&1",$output,$ret);
    $message = implode("\r\n",$output);
    unlink($tmp_file_name);
    error_log("\r\n\r\n".date('Y-m-d H:i:s')."\r\nfile_name: ".$tmp_file_name."\r\nfile_put_contents ret: ".$ret."\r\nexec php -l output:\r\n".$message."\r\n",3,'d:\php-svn-hook-master\tmp\php_svn_hook.log');
    if(stripos($message,'No syntax errors detected') === false ){
      return $message;
    }
  }
}
