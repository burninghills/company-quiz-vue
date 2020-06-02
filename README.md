# company-quiz-vue

## Установка
```
npm install
```

### Компиляция и hot-reload для разработки
```
npm run serve
```

### Компиляция и минификация для публикации
```
npm run build
```

### Компиляция, минификация и создание сборки для веб-компонента
```
npm run build-wc
```

### Линтинг и исправление
```
npm run lint
```


## Публикация
Модуль рассчитан на сборку как веб-компонент. 
Когда собранные файлы находятся в папке проекта, просто добавляйте скрипт
```
<script src="/web-components/company-quiz-vue/dist/company-quiz.min.js"></script>
```

А затем вызывайте в необходимом на странице месте
```
<company-quiz></company-quiz>
```

## Атрибуты
Компонент принимает следующие атрибуты:
* ***theme***  
По умолчанию - *classic*  
Устанавливает цветовую тему для квиза  
Возможные значения:
* *classic*
* *amethyst*
  
  
* ***data-main***  
По умолчанию - */static/quiz_main.json*  
Принимает строку, содержащую ссылку на json-файл главной информации о компании


* ***data-source***  
По умолчанию - */static/quiz_steps.json*  
Принимает строку, содержащую ссылку на json-файл из которого будут формироваться экраны.

* ***data-action***
По умолчанию - */api/MailEngine.php*  
Принимает строку, значение которой будет передано в качестве атрибута *action*.

## Добавление информации о компании
Информация добавляется на основе данных из json-файла, полученного из атрибута *data-main*  
[Пример json-файла для добавления информации о компании](https://github.com/burninghills/company-quiz-vue/blob/master/public/static/quiz_main.json)

## Создание экранов
Экраны создаются на основе информации в json-файле, полученном из атрибута *data-source*  
[Пример json-файла для создания экранов](https://github.com/burninghills/company-quiz-vue/blob/master/public/static/quiz_steps.json)
### Базовая структура
```
{
    "steps": [
        // Здесь находится информация об экранах
    ]
}
```  
### Экраны
#### Типы оформления экранов:
* ***text***
* ***radio***
* ***card***
* ***selects***
* ***text-and-select***  
* ***request***

#### Обязательные ключи:
Данные ключи общие для всех типов экранов.
* ***id*** - Уникальный идентификатор экрана
* ***title*** - Заголовок экрана
* ***desc*** - Краткое пояснение к экрану.
* ***required*** - Обязательный ли экран. Если значение *false*, отображает кнопку пропуска экрана
* ***items_type*** - Тип экрана.

### Структура экранов
#### text
```
{
  "id": 0,
  "title": "Заголовок экрана",
  "desc": "Краткое пояснение к вопросу",
  "required": false,
  "items_type": "text"
}
```

#### radio
```
{
  "id": 0,
  "title": "Заголовок экрана",
  "desc": "Краткое пояснение к вопросу",
  "required": true,
  "items_type": "radio",
  "items": [
    {
      "id": 0,
      "name": "Первый элемент"
    },
    {
      "id": 1,
      "name": "Второй элемент"
    },
    {
      "id": 2,
      "name": "Третий элемент"
    }
  ]
}
```

#### card
```
{
  "id": 0,
  "title": "Заголовок экрана",
  "desc": "Краткое пояснение к вопросу",
  "required": false,
  "items_type": "card",
  "items": [
    {
      "id": 0,
      "name": "Первый Вариант",
      "image": "/img/your-pic-1.jpg"
    },
    {
      "id": 1,
      "name": "Второй вариант",
      "image": "/img/your-pic-2.jpg"
    }
  ]
}
```

#### selects
```
{
  "id": 0,
  "title": "Заголовок экрана",
  "desc": "Краткое пояснение к вопросу",
  "required": true,
  "items_type": "selects",
  "items": [
    {
      "id": 0,
      "label": "Выберите город отправки из списка",
      "keyValue": "Город отправки:",
      "options": [
        {
          "id": 0,
          "value": "Австрия"
        },
        {
          "id": 1,
          "value": "Англия"
        },
        {
          "id": 2,
          "value": "Бельгия"
        }
      ]
    },
    {
      "id": 1,
      "label": "Выберите город доставки из списка",
      "keyValue": "Город доставки:",
      "options": [
        {
          "id": 0,
          "value": "Москва"
        },
        {
          "id": 1,
          "value": "Санкт-Петербург"
        },
        {
          "id": 2,
          "value": "Владивосток"
        }
      ]
    }
  ]
}
```

#### text-and-select
```
{
  "id": 0,
  "title": "Заголовок экрана",
  "desc": "Краткое пояснение к вопросу",
  "required": false,
  "items_type": "text-and-select",
  "items": [
    {
      "id": 0,
      "value": "Выберите значение",
      "text": "Выберите значение"
    },
    {
      "id": 1,
      "value": "Доллары",
      "text": "$"
    },
    {
      "id": 2,
      "value": "Евро",
      "text": "€"
    }
  ]
}
```

#### request
Этим экраном необходимо заканчивать каждый json-файл с информацией об экранах
```
{
  "id": 7,
  "title": "Отлично, осталось только отправить данные!",
  "desc": "В ближайшее время мы свяжемся с Вами и вышлем купон на скидку 5% для выбранных услуг",
  "items_type": "request"
}
```

## История изменений
Историю изменений можно посмотреть [здесь](https://github.com/burninghills/company-quiz-vue/blob/master/CHANGELOG.md)
