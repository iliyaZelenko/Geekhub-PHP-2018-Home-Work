## Что сделал


[Demo тут.](http://e236b03c.ngrok.io/posts/1)

Создал BlogController в котором возврщаю посты блога в формате json с пагинацией.

Потом вот так отображаю посты:

![](https://i.imgur.com/Xc9ySao.png)

Еще там возвращаю роуты, через которые хочу делать запросы на бекенд.

## Дополнительно

Решил сделать фронтенд отдельно от бекенда, общаться через REST API.

На фронтенде используется SSR, точнее Universal/Isomorphic подход, 
то есть при запросе рендерятся на сервере, а дальше как SPA, что дает лучшее SEO и производительность чем обычное SPA.

[Why SSR?](https://ssr.vuejs.org/#why-ssr)

Для CORS использовал [бандл NelmioCorsBundle](https://github.com/nelmio/NelmioCorsBundle) (настроил его в `config/packages/nelmmio_cors.yaml`).






На фронте такие технологии:

- Vue
- Babel
- Nuxt.js
- Vuetify (UI)
- TypeScript
- ESlint + TSLint
- ES 2015+
- Stylus

