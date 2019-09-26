<?php
/**
 * 简单鼠标点击事件 
 * 
 * @package MouseCheck
 * @author caorui
 * @version 1.0.1
 * @link https://www.caorui.top/ddblog/
 */
class MouseCheck_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
	Typecho_Plugin::factory('Widget_Archive')->header = array('MouseCheck_Plugin', 'header');
	Typecho_Plugin::factory('Widget_Archive')->footer = array('MouseCheck_Plugin', 'footer');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
   
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
		$opencheck = new Typecho_Widget_Helper_Form_Element_Radio('opencheck', array('1'=> '是', '0'=> '否'), 1, _t('是否开启:'), _t('配置点击事件是否开启，默认为是'));
        $form->addInput($opencheck);
		$content = new Typecho_Widget_Helper_Form_Element_Text('content', NULL, '富强,民主,文明,和谐,自由,平等,公正,法治,爱国,敬业,诚信,友善', _t('点击内容:'), _t('点击内容，多个用英文逗号“,”隔开'));
        $form->addInput($content);
        $strcolor = new Typecho_Widget_Helper_Form_Element_Text('strcolor', NULL, '#FF0000,#FF7F00,#FFFF00,#00FF00,#00FFFF,#0000Ff,#8B00FF', _t('文字颜色:'), _t('配置点击文字颜色，多个用英文逗号“,”隔开,,为空时默认设置red'));
        $form->addInput($strcolor);
}
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 输出头部css
     * 
     * @access public
     * @return void
     */
    public static function header(){
        echo <<<EOF
		<!-- mousecheck Start -->
		<style>
		    .text-popup {
				animation: textPopup 1s;
				color: red;
				user-select: none;
				white-space: nowrap;
				position: absolute;
				z-index: 99;
		    }
		    @keyframes textPopup {
				100% { opacity: 0; }
				5% { opacity: 1; }
				100% { transform: translateY(-50px); }
			}
			@-moz-keyframes textPopup {
				100% { opacity: 0; }
				5% { opacity: 1; }
				100% { transform: translateY(-50px); }
			}
			@-webkit-keyframes textPopup {
				100% { opacity: 0; }
				5% { opacity: 1; }
				100% { transform: translateY(-50px); }
			}
			@-o-keyframes textPopup {
				100% { opacity: 0; }
				5% { opacity: 1; }
				100% { transform: translateY(-50px); }
			}
		</style>
		<!-- mousecheck End -->
		EOF;
    }
    /**
     * 输出底部
     * 
     * @access public
     * @return void
     */
    public static function footer(){
        $checkState = Typecho_Widget::widget('Widget_Options')->plugin('MouseCheck')->opencheck;
        $stringContent = Typecho_Widget::widget('Widget_Options')->plugin('MouseCheck')->content;
        $strColors = Typecho_Widget::widget('Widget_Options')->plugin('MouseCheck')->strcolor;
        echo <<<EOF
	    <!-- mousecheck Start -->
	    <script>
		var fnTextPopup = function(arr, arrcolor) {
		    if({$checkState} != '1'){
			return;
		    }
		    // arr参数是必须的
		    if(!arr || !arr.length) {
			return;
		    }
		    if(!arrcolor || !arrcolor.length) {
			arrcolor = ['red'];
		    }
		    var sizecolor = arrcolor.length-1;
		    // 主逻辑
		    var index = 0;
		    document.documentElement.addEventListener('click', function(event) {
			var x = event.pageX,
			    y = event.pageY;
			var indexcolor = parseInt(Math.random()*(sizecolor+1),10);
			var clickcolor = arrcolor[indexcolor];
			var eleText = document.createElement('span');
			eleText.className = 'text-popup';
			if(clickcolor){
			    eleText.style.color = clickcolor;
			}
			this.appendChild(eleText);
			if(arr[index]) {
			    eleText.innerHTML = arr[index];
			} else {
			    index = 0;
			    eleText.innerHTML = arr[0];
			}
			// 动画结束后删除自己
			eleText.addEventListener('animationend', function() {
			    eleText.parentNode.removeChild(eleText);
			});
			// 位置
			eleText.style.left = (x - eleText.clientWidth / 2) + 'px';
			eleText.style.top = (y - eleText.clientHeight) + 'px';
			// index递增
			index++;
		    });
		};
		var strcolor = '{$strColors}';
		var arrc = strcolor.split(',');

		var str = '{$stringContent}'
		var arra = str.split(',');
		fnTextPopup(arra, arrc);
	    </script>
	<!-- mousecheck End -->
	EOF;
    }
}
