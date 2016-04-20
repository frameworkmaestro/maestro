<?php
/**
 * Registra Autoload
 */
spl_autoload_register(array('Maestro\Manager', 'autoload'));

function _M($msg, $params = NULL)
{
    return Maestro\Manager::getMessage($msg, $params) ? : $msg;
}

function tdump($var)
{
    Maestro\Manager::getPage()->addDump(Tracy\Dumper::toHtml($var));
}

function mdump($var, $tag = null)
{
    if ($tag) {
        $tag = '[' . $tag . ']';
    }
    $msg = $tag . Tracy\Dumper::toText($var , array('truncate' => 400));
    Maestro\Manager::dump($msg);
}

function mtrace($var)
{
    Maestro\Manager::trace($var);
}

function mtracestack()
{
    return Maestro\Manager::tracestack();
}

function mrequest($vars, $from = 'ALL', $order = '')
{
    if (is_array($vars)) {
        foreach ($vars as $v) {
            $values[$v] = mrequest($v, $from);
        }
        return $values;
    } else {
        $value = NULL;
// Seek in all scope?
        if ($from == 'ALL') {
// search in REQUEST
            if (is_null($value)) {
                $value = isset($_REQUEST[$vars]) ? $_REQUEST[$vars] : NULL;
            }

            if (is_null($value)) {
// Not found in REQUEST? try GET or POST
// Order? Default is use the same order as defined in php.ini ("EGPCS")
                if (!isset($order)) {
                    $order = ini_get('variables_order');
                }

                if (strpos($order, 'G') < strpos($order, 'P')) {
                    $value = isset($_GET[$vars]) ? $_GET[$vars] : NULL;

// If not found, search in post
                    if (is_null($value)) {
                        $value = isset($_POST[$vars]) ? $_POST[$vars] : NULL;
                    }
                } else {
                    $value = isset($_POST[$vars]) ? $_POST[$vars] : NULL;

// If not found, search in get
                    if (is_null($value)) {
                        $value = isset($_GET[$vars]) ? $_GET[$vars] : NULL;
                    }
                }
            }

// If we still didn't have the value
// let's try in the global scope
            if ((is_null($value) ) && ( ( strpos($vars, '[') ) === false)) {
                $value = isset($_GLOBALS[$vars]) ? $_GLOBALS[$vars] : NULL;
            }

// If we still didn't has the value
// let's try in the session scope

            if (is_null($value)) {
                if ($vars) {
                    $value = isset($_SESSION[$vars]) ? $_SESSION[$vars] : NULL;
                }
            }
        } else if ($from == 'GET') {
            $value = isset($_GET[$vars]) ? $_GET[$vars] : NULL;
        } elseif ($from == 'POST') {
            $value = isset($_POST[$vars]) ? $_POST[$vars] : NULL;
        } elseif ($from == 'SESSION') {
            $value = isset($_SESSION[$vars]) ? $_SESSION[$vars] : NULL;
        } elseif ($from == 'REQUEST') {
            $value = isset($_REQUEST[$vars]) ? $_REQUEST[$vars] : NULL;
        }
        return $value;
    }
}

function shutdown()
{
    $error = error_get_last();
    /*
     * TODO: handler this error message
     */
    if ($error['type'] & (Maestro\Manager::getConf('debug.severity'))) {
        if (Maestro\Manager::isAjaxCall()) {
            $ajax = Maestro\Manager::getAjax();
            $ob = ob_get_clean();
            if ($ajax->isEmpty()) {
                $ajax->setType('page');
                $ajax->setData($ob);
            }
            $result = $ajax->returnData();
            echo $result;
        }
    }
}
