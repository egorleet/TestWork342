# TestWork 342
TestWork 342 t.me/egorleet
- Тема: storefront
- WordPress 5.9.3
- WooCommerce 6.4.0


Backend:
1. При помощи плагина woocommerce реализовать custom fields для продукта.
ВНИМАНИЕ! не использовать плагины для самого “custom field” создавать все при помощи кода.

2. В продукте (backend) должны появится дополнительные поля с :
картинкой + кнопкой remove для её удаления.
время когда был создан продукт, а именно type=”date”.
select c выбором типа продукта (rare, frequent, unusual)
<br>=<br>
Сделал, реализовано через woocommerce_wp_text_input и woocommerce_wp_select , поле для загрузки изображения реализовано через input с классом hidden, а сама загрузка происходит в поле input type="file" через ajax функцию ajax_create_product (если не задан id, то функция работает на добавление товара, об этом ниже), также при загрузке задается изображение товара.
Код php: /inc/woocommerce-custom-fields.php
Код js: /js/woocommerce_inputs.js

3. Добавить кнопку (JS) для полной очистки custom полей.
<br>=<br>
Сделал, также заметил что просто очистить и сохранить недостаточно, нужно еще чистить то, что лежит через additional field http://joxi.ru/52ajeKQClk7p7A , его чищу через .each + удаляется миниатюру

4. Добавить (JS) кнопку, которая будет реализовывать submit(update)
<br>=<br>
Сделал, реализовал через .click();

Скриншот всего:
<br>=<br>
http://joxi.ru/gmvPVEeCe1P48A


Frontend:
1. Реализован показ миниатюры http://joxi.ru/KAxPMQ5CVKPxyA
2. Форма реализована http://joxi.ru/BA0kbw4C1pnEv2, через отдельный темплэйт /template-create-product.php , работает по ajax (/js/woocommerce_form.js) (стили старался использовать те, которые уже имеются в теме storefront)
3. Вывод товаров на главной реализован на front-page.php http://joxi.ru/MAjPekMCdkPbYm через do_shortcode()

В завершении скажу спасибо, задание было очень интересное, мне понравилось! Желаю вам отличного дня :)



