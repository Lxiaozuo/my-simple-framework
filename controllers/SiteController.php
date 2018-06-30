<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/6/5
 * Time: 14:09
 */

namespace app\controllers;

use app\models\User;
use sf\web\Contoller;

class SiteController extends Contoller
{
    public function actionIndex()
    {
        var_dump(123);die;
        // 实现file缓存
        $cache = \Sf::createObject('cache');
//        $cache->set('test', '测试缓存');
        $res = $cache->exists('test');
        var_dump($res);
    }

    public function actionTest()
    {
        $content = <<<VIEW
<?php
\$name = 'test';
\$str = "{{ \$name }}";
?>

<html>
  <body>{{ \$name }}</body>
<html>
VIEW;
        $result = '';
//        $arr = token_get_all($content);
        foreach (token_get_all($content) as $token) {
            if (is_array($token)) {
                var_dump($token);die;
                list($id, $content) = $token;
                if ($id == T_INLINE_HTML) {
                    $content = preg_replace('/{{(.*)}}/', '<?php echo $1 ?>', $content);
                }
                $result .= $content;
            } else {
                $result .= $token;
            }
        }

    }

    protected function compileStatements($content)
    {
        return preg_replace_callback(
            '/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', function ($match) {
            return $this->compileStatement($match);
        }, $content
        );
    }

}