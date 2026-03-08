<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");
   // Инициализируем JS-библиотеку Bitrix с модулем AJAX 
   CJSCore::Init(array('ajax'));
   // Задаем идентификатор для AJAX-запроса
   $sidAjax = 'testAjax';
// Проверяем, пришел ли AJAX-запрос. Ищем параметр 'ajax_form' в запросе и сверяем его значение с ожидаемым идентификатором
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
   // Очищаем буфер вывода перед выводом AJAX-результата
   $GLOBALS['APPLICATION']->RestartBuffer();
   /*
   Формируем и отправляем JSON-ответ клиенту:
   'RESULT' - содержит строку 'HELLO'
   'ERROR' - поле для передачи ошибок, в данном случае ошибок нет
   */
   echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));
   // Завершаем выполнение скрипта
   die();
}

?>
<!-- HTML-разметка страницы -->
<div class="group">
   <!-- Блок для отображения результата AJAX‑запроса -->
   <div id="block"></div >
   <!-- Индикатор загрузки, показывается во время выполнения AJAX‑запроса -->
   <div id="process">wait ... </div >
</div>
<script>
   // Включаем режим отладки в библиотеке Bitrix (BX)
   window.BXDEBUG = true;
// Функция для запуска AJAX-запроса   
function DEMOLoad(){
   // Скрываем блок с результатом
   BX.hide(BX("block"));
   // Показываем блок с индикатором загрузки
   BX.show(BX("process"));
   // Отправляем AJAX-запрос с параметром 'ajax_form' 
   BX.ajax.loadJSON(
      '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
      // При получении ответа вызываем функцию DEMOResponse
      DEMOResponse
   );
}
// Функция обработки ответа от сервера после AJAX-запроса
function DEMOResponse (data){
   // Выводим данные ответа в консоль браузера для отладки
   BX.debug('AJAX-DEMOResponse ', data);
   // Заполняем блок с результатом - значением 'RESULT'
   BX("block").innerHTML = data.RESULT;
   // Показываем блок с результатом
   BX.show(BX("block"));
   // Скрываем блок с индикатором загрузки
   BX.hide(BX("process"));
   // Генерируем событие 'DEMOUpdate' на элементе с id="block"
   BX.onCustomEvent(
      BX(BX("block")),
      'DEMOUpdate'
   );
}
// Код, который выполняется после полной загрузки DOM-дерева
BX.ready(function(){
   /*
   BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
      window.location.href = window.location.href;
   });
   */
   // Скрываем блоки с результатом и с индикатором загрузки
   BX.hide(BX("block"));
   BX.hide(BX("process"));
    // Используем делегирование событий
    BX.bindDelegate(
      // Назначаем обработчик клика на элементы с классом 'css_ajax' внутри document.body
      document.body, 'click', {className: 'css_ajax' },
      // Обеспечиваем совместимость кода с разными браузерами
      function(e){
         if(!e)
            e = window.event;
         // Запускаем AJAX‑запрос через функцию DEMOLoad
         DEMOLoad();
         // Отменяем стандартное действие браузера при клике
         return BX.PreventDefault(e);
      }
   );
   
});

</script>
<!-- Элемент, клик по которому запускает AJAX‑запрос -->
<div class="css_ajax">click Me</div>
<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
