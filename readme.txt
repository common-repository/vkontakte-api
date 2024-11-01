=== VKontakte API - crossposting, comments, social buttons, login ===
Contributors: webcraftic
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VDX7JNTQPNPFW
Tags: vkontakte, facebook, crosspost, comments, social, share, vk.com
Requires at least: 4.8
Tested up to: 5.6
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The Vkontakte API plugin is designed for the regular users who need to add any Vkontakte widgets on their websites.

== Description ==

The Vkontakte API plugin is designed for the regular users who need to add any Vkontakte widgets on their websites. There’s no need to be an expert in programming nor asking someone for assistance, as the plugin will be easily installed in a few clicks.

= CROSSPOSTING =

Post your article previews in the group or Vkontakte profile. When you create an article in WordPress and click “Post”, the plugin automatically posts the article preview on your social media pages.

* You can create an article preview with the header, link, short description, images and hashtags.
* Images for the article preview can be added from the external sources via the links.
* Automatic hashtag adding from the WordPress tags and sections.
* You can postpone the article preview setting a delay after publication.
* The plugin will automatically edit the article preview you’ve posted in case of any changes in the original text

= COMMENTS =
You can install Vkontakte and Facebook Comments widget on your WordPress site. The Vkontakte API plugin combines several comments widget (including regular WordPress comments). The user can choose the most comfortable commenting system by clicking on the necessary tab.

* Comments widget can stretch and adjust to the width of your template; great performance on mobile devices.
* If you don’t need regular WordPress comments, you can easily disable it and use social media widgets instead.
* The plugin will notify the administrator about new comments, by sending a notification e-mail.

= WIDGETS AND LIKES =

In addition to the VK widget, the plugin supports social buttons from other popular social media (vk.com, fb.com, plus.google.com, twitter.com, mail.ru, ok.ru). You can find the full list of supported features below:

* Like – Vkontakte;
* Share – Vkontakte;
* Like – Facebook;
* Retweet – Twitter;
* Like – Moi Mir (Mail.RU);
* Like – Odnoklassniki.

= LOGIN/REGISTRATION =

Adds the “Login using Vkontakte” button to the login page. This feature works perfectly for both the login and registration page.
• VK

= ADDITIONAL FEATURES =

• Supports multilanguage;
• HTML5 tag cloud;
• Disables post revisions.

#### BIG THANKS ####
Many thanks to [Евгений Забродский (kowack)](https://darx.net/), for his great contribution to the development of this plugin.

#### Recommended our plugins ####

We invite you to check out a few other related free plugins that our team has also produced that you may find especially useful:

* [Clearfy – WordPress optimization plugin and disable ultimate tweaker](https://wordpress.org/plugins/clearfy/)
* [Disable Comments for Any Post Types (Remove Comments)](https://wordpress.org/plugins/comments-plus/)
* [Cyrlitera – transliteration of links and file names](https://wordpress.org/plugins/cyrlitera/)
* [Cyr-to-lat reloaded – transliteration of links and file names](https://wordpress.org/plugins/cyr-and-lat/ "Cyr-to-lat reloaded")
* [Disable admin notices individually](https://wordpress.org/plugins/disable-admin-notices/ "Disable admin notices individually")
* [Hide login page](https://wordpress.org/plugins/hide-login-page/ "Hide login page")
* [Disable updates, Disable automatic updates, Updates manager](https://wordpress.org/plugins/webcraftic-updates-manager/)
* [WordPress Assets manager, dequeue scripts, dequeue styles](https://wordpress.org/plugins/gonzales/  "WordPress Assets manager, dequeue scripts, dequeue styles")

== Installation ==

1. Upload `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Error: VK Error. 5 User authorization failed: invalid session? =

Log out from the VK page and sign in again using your login and password. Then try to re-gain access token in VK API plugin.

= Does this plugin support multisites? =

No, it doesn’t.


== Screenshots ==

1. Модули.

== Changelog ==
= 4.2.3 =
* Обновления безопасности
* Мелкие исправления

= 4.2.2 =
* Откат к предыдущей версии

= 4.2.1 =
* Откат к предыдущей версии

= 4.1.2 =
* Исправлен баг, когда виджет комментариев ВК отображался не всегда

= 4.1.1 =
* Исправлена ошибка 'non-numeric value encountered' при подсчёте количества комментариев

= 4.1.0 =
* Обновление VK API до версии 5.95
* Совместимость с Gutenberg
* Убрана поддержка Google+
* Изменилась логика вывода кнопок "Мне нравится". Теперь блок кнопок может отображаться только над постом или под постом
* Исправлены проблемы с авторизацией через ВК
* Исправлены проблемы с кросспостингом
* Добавлена совместимость с последней версией Wordpress

= 4.0.1 =
* Исправлены проблемы с кнопкой авторизоваться через Вконтакте
* Исправлены проблемы с формой комментарив от Вконтакте

= 4.0.0 =
* Исправлены баги проверкой токена, группы и id приложения
* Добавлена совместимость с php 7.2
* Добавлена совместимость с последней версией Wordpress

= 3.32.5.1 - 3.32.5.9 =
* Исправление мелких недочётов и совместимости с сторонними темами и плагинами

= 3.32.5 =
* Возврат с TLS 1.2 к TLS 1 если на сервере не актуальное ПО.
* Запросы к API без Ключа доступа не будут идти по безопасному протоколу
* Кросспост: опция или добавлять заголовок статьи
* Кросспост: опция или конвертировать категории в хештеги
* Кроссопст: все спец. символы и пробелы заменяются символом подчёркивания
* Возврат поддержки устаревших версий WP
* Полный отказ от jQuery в сторону нативного кода
* Комментарии: добавлена опция переключателя

= 3.32.0 =
* Разделение плагина на модули
* Автоматическое определение ID группы ВК по ссылке
* Автоматическая проверка ID приложения ВК на валидность
* Автоматическая проверка Ключа Доступа на валидность
* Поддержка как PHP 5.x.x, так и PHP 7.x.x
* Переписан код отправки исходящих запросов
* Переписан код авторизации через ВК
* Улучшена адаптивность
* Проверка домена на доступность в ВК
* Прочие исправления

= 3.31.6 =
* Добавлена возможность отложенной публикации

= 3.31.5 =
* Исправление ошибки "ВК отвергнул загрузку фото"

= 3.31.1 - 3.31.4 =
* Поддержка РНР 5.2
* Изменение преобразования html2text
* Востановление совместимости с WooCommerce

= 3.31 =
* Комментарии: внедрение адаптивности.
* Кросспост: попытка избавится от ошибки "Security Breach2".
* Кросспост: перерабока функции html2text.
* Кросспост: выбор какие типы записи кросспостить.
* Кросспост: проверка или запись защищена паролем.
* Кросспост: увеличение таймаута с 5 до 25 секунд.
* Кросспост: переработка обработки ошибок.
* Общее: Исправлены мелкие баги.

= 3.30 =
* Совместимость с отзывами WooCommerce и мелкие улучшения.

= 3.29 =
* Обновление записи при повторном кросспосте.

= 3.28 =
* Обновлены соц. кнопки
* Мелкие правки
* fix vk share button in Opera browser
* fix vk avatar display

= 3.27 =
Fix: при text length = 0 не поститься заголовок
Переводы

= 3.26 =
Fix: fb comments, php safe mode,

= 3.24 =
Мелкие изменения.

= 3.23 =
Кросспост нескольких изображений и мелкие улучшения.

= 3.21 =
Опция для кросспоста тегов.

= 3.20 =
ВК заблочил страрую верисю АПИ :\

= 3.11 - 1.19 =
Улучшения.

= 3.10 =
Улучшения и отображение каптчи.

= 3.9 =
Небольшое улучшение.

= 3.8 =
Актуализация под новые версии ВКонтакте и Wordpress.
Теперь уведомления присылаются не только админу сайта, но и автору записи.
Теперь не кросспостятся записи из bbPress.

= 3.7 =
Починен счетчик комментариев.

= 3.6 =
Всякие улучшения и исправления появившихся багов.

= 3.5 =
Больше опций.
Улучшения.

= 3.4 =
Исправления.

= 3.3 =
Исправления.
Поддержка всех типов постов.

= 3.2 =
Убраны старые баги, добавлены новые.
Вывод ошибок кросспостинга, если он не сработал.

= 3.0 =
Мажорное обновление.
Троеточие в конце статьи.
Теперь можно указывать именную ID группы.
Вместо картинки поста берётся миниатюра, если такова присутствует.
Все скрипты асинхронны.
Соц.кнопки теперь "inline-block" вместо "float".
Опция "Первыми показывать комментарии Facebook-а".
При кросспостинге добавляется заголовок статьи.
(Beta) Запланированные записи (их кросспост и опция)
(Beta) Декодировать кириллические ссылки при кросспосте.

= 2.7 =
Улучшения + мелкие исправления.

= 2.6 =
Улучшения.

= 2.5 =
* больше настроек кросспостинга;
* фикс счётчика комментариев;
* изменение запроса прав на "Access Token".

= 2.4 =
Remove debug code

= 2.3 =
Better.

= 2.2 =
Кросспостинг с изображениями.

= 2.1 =
Подгонка плагина под WP 3.4 и тест кросспостинга.

= 2.0 =
* Комментарии от FB.
* Виджет "Сообщества" FB.
* Социальные кнопки: Mail.ru, Одноклассники, Twitter, Яндекс.

= 1.27 =
* Исправление вывода шорткодов.
* Мелкие улучшения.
* Добавлена кнопка "Мне нравится" от Facebook.
* Добавлена кнопка "+1" от Google.
* Добавлен шорткод [vk_like] ( [vk_like id="123456"], где 123456 - уникальный айдишник кнопки ).

= 1.24 - 1.26 =
* Исправление исправлений. А также:
* У тех у кого работает вход - исправление роли нового пользователя.
* Опция ширины виджета статистики.
* Опция кнопок на каждой страницы.
* Больше опций виджета тегов.
* Если у темы wp-login.php свой - нету кнопок.
* Ссылочки переписаны на VK.com.

= 1.23 =
* Переконвертировал всё в Win-формат.

= 1.22 =
* Fix.

= 1.21 =
* Облако тегов ( многие плагины тегов не поддерживают версию WordPress-a 3.3 ).

= 1.20 =
* Исправление отображения в некоторых темах.

= 1.19 =
* Исправление ограничения ВКонтактом длины строки.

= 1.18 =
* Мелкое исправление редиректа при входе на сайт через ВК.
* Опцию включения/отключения входа через ВК.
* Так же по просьбам кнопка "Сохранить" ( Share ).
* Виджет "Последние комментарии ВКонтакте".

= 1.17 =
* Добавлена привязка аккаунта ВКонтакте.

= 1.16 =
* Очередное улучшение производительности.
* Виджет авторизации (бета-версия).

= 1.15 =
* style-fix

= 1.14 =
* mini-fix

= 1.13 =
* Вывод количества комментариев ВКонтакта.
* Отдельные настройки для каждой страницы.

= 1.12 =
* better

= 1.11 =
* better

= 1.10 =
* Added login\register widget. Can be enabled from options page.

= 1.9 =
* Fixed (if WP installed in sub directory)

= 1.8 =
* Admin mail-notification

= 1.7 =
* Add more options, hide wordpress comments if you wish

= 1.6 =
* Simple fix, better performance

= 1.5 =
* Fix "closed" commenting on page plus decorations.

= 1.4.1 =
* Translate fix.

= 1.4 =
* Fix something and add somethis options ^^.

= 1.3 =
* Add "I like" button.
* Add Widgets "Groups" and "More popular of 'I like' button statistics"

= 1.2 =
* Fix jQuery.

= 1.0 =
* Stable.

= 0.9 =
* "The plugin first time seeing this grey world. =)".

== Upgrade Notice ==

= 3.32.5 =
  Возврат с TLS 1.2 к TLS 1 если на сервере не актуальное ПО.
| Запросы к API без Ключа доступа не будут идти по безопасному протоколу
| Кросспост: опция или добавлять заголовок статьи
| Кросспост: опция или конвертировать категории в хештеги
| Кроссопст: все спец. символы и пробелы заменяются символом подчёркивания
| Возврат поддержки устаревших версий WP
| Полный отказ от jQuery в сторону нативного кода
| Комментарии: добавлена опция переключателя

= 3.32.0 =
* Код переписан. Перепроверьте и пересохраните настройки.

= 3.31.6 =
* Добавлена возможность отложенной публикации

= 3.31.5 =
Исправление ошибки "ВК отвергнул загрузку фото"

= 3.31.4 =
Востановление совместимости с WooCommerce + мелочи

= 3.31 =
Укажите какие типы записей кросспостить! Перепроверьте и сохраните настройки.

= 3.29 =
Обновление записи при повторном кросспосте.

= 3.28 =
Обновлены соц. кнопки, мелкие правки, исправлена кнопка поделится в Opera браузере, исправленно отображения аватара в комментариях при логине через ВК

= 3.27 =
Fix: при text length = 0 не поститься заголовок
Переводы

= 3.26 =
Исправлено: комментарии фейсбук, php safe mode.

= 3.25 =
Мелкие улучшения.

= 3.24 =
Мелкие изменения.

= 3.23 =
Кросспост нескольких изображений и мелкие улучшения.

= 3.21 =
Опция для кросспоста тегов.

= 3.20 =
ВК заблочил страрую верисю АПИ :\

= 3.19 =
Улучшения.

= 3.10 =
Улучшения и отображение каптчи.

= 3.9 =
Небольшое улучшение.

= 3.8 =
Актуализация под новые версии.

= 3.7 =
Починен счетчик комментариев.

= 3.6 =
Возможны баги. Был не в чистом сознании.

= 3.5 =
Улучшения.

= 3.4 =
Исправления.

= 3.3 =
Поддержка всех типов постов.
Исправления.

= 3.2 =
Вывод ошибок кросспостинга, если он не сработал.
Убраны старые баги, добавлены новые.

= 3.0 =
Переработана страница настроек.
При необходимости проверьте и обновите настройки.

= 2.7 =
Улучшения + мелкие исправления.

= 2.6 =
Улучшения.

= 2.5 =
Больше настроек кросспостинга, фикс счётчика комментариев, изменение запроса прав на "Access Token".

= 2.4 =
Убран отладочный код.

= 2.3 =
Решение проблем у тех у кого они были.

= 2.2 =
Кросспостинг с изображениями.

= 2.1 =
Подгонка плагина под WP 3.4 и тест кросспостинга.

= 2.0 =
Facebook, социальные кнопки: Mail.ru, Одноклассники, Twitter, Яндекс.

= 1.27 =
* Исправление вывода шорткодов.
* Мелкие улучшения.
* Добавлена кнопка "Мне нравится" от Facebook.
* Добавлена кнопка "+1" от Google.
* Добавлен шорткод [vk_like] ( [vk_like id="123456"], где 123456 - уникальный айдишник кнопки ).

= 1.24 - 1.26 =
* Исправление исправлений. А также:
* У тех у кого работает вход - исправление роли нового юзера.
* Опция ширины виджета статистики.
* Опция кнопок на каждой страницы.
* Больше опций виджета тегов.
* Если у темы wp-login.php свой - нету кнопок.
* Ссылочки переписаны на Vk.com.

= 1.23 =
* Переконвертировал всё в Win-формат.

= 1.22 =
* Fix.

= 1.21 =
* Облако тегов ( многие плагины тегов не поддерживают версию WordPress-a 3.3 ).

= 1.20 =
* Исправление отображения в некоторых темах.

= 1.19 =
* Исправление ограничения ВКонтактом длины строки.

= 1.18 =
* Мелкое исправление редиректа при входе на сайт через ВК.
* Опцию включения/отключения входа через ВК.
* Так же по просьбам кнопка "Сохранить" ( Share ).
* Виджет "Последние комментарии ВКонтакте".

= 1.17 =
* Добавлена привязка аккаунта ВКонтакте.

= 1.16 =
* Очередное улучшение производительности.
* Виджет авторизации (бета-версия).

= 1.15 =
* Вывод количества комментариев ВКонтакта.
* Отдельные настройки для каждой страницы.
* Микро-исправление.

= 1.14 =
* Вывод количества комментариев ВКонтакта.
* Отдельные настройки для каждой страницы.
* Микро-исправление.

= 1.13 =
* Вывод количества комментариев ВКонтакта.
* Отдельные настройки для каждой страницы.

= 1.12 =
* Делаем лучше

= 1.11 =
* Делаем лучше

= 1.10 =
* Добавлен виджет авторизации\регистрации. Включается в настройках плагина.

= 1.9 =
* Исправления (одно из них: если ВП установлен не в корневую директорию)

= 1.8 =
* Сообщения админу о новых комментариях. Продолжение следует =)

= 1.7 =
* Больше опций, скрытие комментариев Wordpress-a по желанию.

= 1.6 =
* Доработки

= 1.5 =
* Виджет комментариев не отображается если стоит запрет на комментирование в настройках страницы Wordpress.
* Плюшки =)

= 1.4.1 =
* Ошибочка перевода ^_^.

= 1.4 =
* Чу-чуть подправил и прибавил пару опций.

= 1.3 =
* Добавлена кнопка "Мне нравиться".
* Добавлены 2 виджета: первый показывает вашу группу ВКонтакте (название, участники, новости), второй - самое популярное исходя из статистики кнопки "Мне нравиться".

= 1.2 =
* Fix jQuery.

= 1.0 =
* Stable.

= 0.9 =
* First release.
