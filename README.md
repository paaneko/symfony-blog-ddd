# Blog
>Modular Monolith + CQRS + Hexagonal + DDD + EDA

```scala
src
├── Auth // Bounded Context: Все що стосується взаємодії з юзером, аунтифікації
│        └── User // Модуль юзера
│           ├── Application 
│           │      ├── Event
│           │      │      └── OnUserVerifiedEvent.php
│           │      ├── EventSubscriber
│           │      │      └── OnAddArticleRequestedEventSubscriber.php
│           │      ├── Service
│           │      └── UseCase
│           │          ├── Add
│           │          └── Get
│           ├── Domain
│           │      ├── Entity
│           │      │      ├── Id.php
│           │      │      ├── IdType.php
│           │      │      └── User.php
│           │      ├── Repository
│           │      └── ValueObject
│           └── Infrastructure
│               ├── Controller
│               │      └── Api
│               └── Repository
├── Blog // Bounded Context: Все що стосується блогу
│       ├── Article // Модуль статті
│       │      ├── Application
│       │      │      ├── Event
│       │      │      │      └── OnArticleAddRequestedEvent.php
│       │      │      ├── EventSubscriber
│       │      │      │      └── OnUserVerifiedEventSubscriber.php
│       │      │      ├── Service
│       │      │      └── UseCase
│       │      ├── Domain
│       │      │      ├── Entity
│       │      │      ├── Event
│       │      │      │      ├── ArticleCloneCreatedEvent.php
│       │      │      │      └── ArticleCreatedEvent.php
│       │      │      ├── Repository
│       │      │      └── ValueObject
│       │      └── Infrastructure
│       │          ├── Controller
│       │          │      └── Api
│       │          └── Repository
│       ├── Category
│       │      ├── Application
│       │      │      ├── Service
│       │      │      └── UseCase
│       │      │          └── Add
│       │      ├── Domain
│       │      │      ├── Entity
│       │      │      │      ├── Category.php
│       │      │      │      ├── Id.php
│       │      │      │      └── IdType.php
│       │      │      ├── Repository
│       │      │      └── ValueObject
│       │      └── Infrastructure
│       │          ├── Controller
│       │          │      └── Api
│       │          └── Repository
│       ├── Section
│       │      ├── Application
│       │      │      ├── Service
│       │      │      └── UseCase
│       │      │          └── Add
│       │      ├── Domain
│       │      │      ├── Entity
│       │      │      │      ├── Id.php
│       │      │      │      ├── IdType.php
│       │      │      │      └── Section.php
│       │      │      ├── Repository
│       │      │      └── ValueObject
│       │      └── Infrastructure
│       │          ├── Controller
│       │          │      └── Api
│       │          └── Repository
│       └── Shared
│           └── Domain
│               ├── Entity
│               │      └── ValueObject
│               │          ├── CategoryId.php
│               │          └── SectionId.php
│               └── Providers
│                   ├── CategoryIdProvider.php
│                   └── SectionIdProvider.php
├── Image // Bounded Context: Все що стосується картинок
│       ├── Application
│       │      ├── EventSubscriber
│       │      │      └── OnArticleCreatedEventSubscriber.php
│       │      ├── Service
│       │      └── UseCase
│       │          ├── SetUsed
│       │          └── Upload
│       ├── Domain
│       │      ├── Entity
│       │      │      ├── Id.php
│       │      │      ├── IdType.php
│       │      │      └── Image.php
│       │      ├── Repository
│       │      ├── Service
│       │      └── ValueObject
│       └── Infrastructure
│           ├── Controller
│           │      └── Api
│           │          └── UploadImageController.php
│           └── Repository
├── Search // Bounded Context: Все що стосується пошуку
│       ├── Application
│       ├── Domain
│       └── Infrastructure
└── SharedKernel
    ├── Aggregate
    │       └── AggregateRoot.php
    ├── Event
    │       └── DomainEventInterface.php
    └── ValueObject
        ├── AggregateRootId.php
        ├── ImageValueObject.php
        └── UserValueObject.php
```

### Ініціалізація Проекту

Для побудови, встановлення залежностей та запуску проекту виконайте:

```sh
make init
```

# Bounded Contexts and Modules

## Bounded Context: Auth

### Module: User
Модуль для взаємодії з користувачем, основна entity всього бізнесу. У різних контекстах користувач може називатися по-різному (e.g., автор, покупець, клієнт, гаманець).  
Однак усі ці назви - просто аліаси до користувача. Якщо іншим модулям потрібно отримати інформацію про користувача або виконати будь-яку дію з ним, вони повинні звертатися до цього контексту.  
**Наприклад**: `Article` використовує **root aggregate id** юзера під назвою `AuthorId`.

## Bounded Context: Blog

### Module: Article
Модуль для взаємодії зі статтею, основний модуль бізнесу. Він є кластером, і всі його entity повинні вважатися єдиним цілим. Цей кластер має один і тільки один **root aggregate** - `Article`, який містить в собі entity `Comment`. Коментар не може бути окремим модулем або **root aggregate**, бо він не може існувати без статті.  
Щоб уникнути порушення **бізнес-invariant** та забезпечити атомарність, розробник **повинен** дістати **root aggregate** і викликати метод додавання на ньому і зберегти в одній транзакції.  
> **Use Cases:**  
>`@see` [Add Comment Handler](src/Blog/Article/Application/UseCase/AddComment/Handler.php)  
>`@see` [Duplicate Article Handler](src/Blog/Article/Application/UseCase/Duplicate/Handler.php)

>Щоб униктути ситуації коли прийшов запрос за створення статті з юзером якого не існує. В [Add Article Controller](src/Blog/Article/Infrastructure/Controller/Api/AddArticleController.php) діспатчиться івент [On Article Add Requested Event](src/Blog/Article/Application/Event/OnArticleAddRequestedEvent.php), який слухає [On Add Article Requested Event Subscriber](src/Auth/User/Application/EventSubscriber/OnAddArticleRequestedEventSubscriber.php) в модулі `User`, якщо юзер існує, модуль дає добро на створення і діспатчить івент [On User Verified Event](src/Auth/User/Application/Event/OnUserVerifiedEvent.php), який вже слухає модуль `Article` з [On User Event Verified Event Subscriber](src/Blog/Article/Application/EventSubscriber/OnUserVerifiedEventSubscriber.php), який додатково перевіряє, чи існує uuid категорії або секції. Якщо все його влаштовує, то комманда на створення закидується в `Message Bus` де згодом буде опрацьована в [хендлері](src/Blog/Article/Application/UseCase/Create/Handler.php) і після успішного створення діспатчить всі створені доменні івенти e.g. [Article Created Event](src/Blog/Article/Domain/Event/ArticleCreatedEvent.php), а в той самий час, цей івент слухають модулі `Image` та `Search`, що описані трішки піздніше.

### Module: Category
Модуль категорії не є кластером, оскільки він має тільки один **root aggregate** - `Category`. Категорія може існувати самостійно, навіть без жодної статті.

### Module: Section
Так само, як і категорія, секція має тільки один **root aggregate** - `Section`. Однак, стаття може не належати до жодної секції.

### Shared
Цей модуль не належить до бізнесу і не несе ніякої бізнес-цінності. Це набір загальних об'єктів, які може використовувати будь-який модуль у контексті блогу.  
Наприклад, модулю `Article` потрібно дізнатися, чи існує категорія, id якої він отримав через API.  

> **Use Cases:**  
> `@see` [Create Article Hanler](src/Blog/Article/Application/UseCase/Create/Handler.php)  
> `@see` [Category Id Provider](src/Blog/Shared/Domain/Providers/CategoryIdProvider.php), [Section Id Provider](src/Blog/Shared/Domain/Providers/SectionIdProvider.php) 

Також в `Shared` знаходяться **aggregate root id** всіх модулів в контексті `Blog`, взявши звідти id модуль-споживач отримує можливість використати **aggregate root id** при цьому не торкнувшись доменного слою модуля.

## Bounded Context: Image

### Module: Image
> Цей контекст може бути названий, як `Media` у майбутньому. Зачача -  займатися менеджментом медіа всього сайту (e.g. баннери, відео, колекції картинок). Та забезпечувати хостинг картинок, генерацію URL, стиснення, перетворення форматів (jpg -> webp), створення різних розширень картинок для різних пристроїв. Може містити в собі модулі: ImageCollection, Video і т.д. Поки ми назвемо цей контекст `Image`.

Фронтенд звертається до модуля `Image` для того, щоб `upload` картинку. Задача модуля - зберегти картинку в будь-яку файлову систему, та створити посилання в бд (при цьому в стовпчику `is_used` поставити false). Якщо картинка не використовується жодним іншим модулем (`is_used` = false), скрипт щотиждня проходиться та видаляє їх з бд та файлової системи.  
Модуль використовує event subscriber для оповіщення про використання картинки.
> **Use Case:**  
> `@see` [On Article Created Event Subscriber](src/Image/Application/EventSubscriber/OnArticleCreatedEventSubscriber.php)

Після отримання івенту про створення статті від модуля `Article` контексту `Blog`, `Event Subscriber` отримує інформацію яка картинка використалася в статті і закидуює комманду на зміну статусу в `Message Bus`.

>Наразі `Message Bus` працює в синхронному режимі, але в майбутньому можливо включити в асинхроний режим, де всі об'экти типу `Сommand` будуть серіалізовані та закинуті в чергу, наприклад `Redis`. Далі `Message Broker` буде брати з черги певну кількість комманд та розкидувати між воркерами, поки ці комманди не будуть опрацьовані `Handler`.  
> **P.S. Тому важливо, щоб об'єкт `Сommand` мав в собі поля примітивних типів. Щоб не було проблем при серіалізаціі та десеріалізації. Але це не точно.**

## Bounded Context: Search

> В майбутньому якщо сайт буде розвиватись і буде створений інший контекст `Merch` або щось інше. І буде бізнес потреба зробити пошук, то в контексті `Search` повинен створитися модуль `Merch`, який вже буде відображати пошук саме в цьому контексті не торкаючись інших, вже існуючих.
> 
### Module: Blog
Цей модуль відображає пошукову систему `Bounded Context` - `Blog`. Тут може використовуватись redis, elasticsearch, або щось інше. І найголовніше те, що контекст `Blog` навіть не знає який рушій використовує пошук, ба більше, він не навіть знає, що у користувачів є можливість шукати блоги в пошуку.  
>Цей модуль підписаний на івент [Article Created Event](src/Blog/Article/Domain/Event/ArticleCreatedEvent.php). І все що йому потрібне, це `article title` та `author name` і найголовніше - це унікальний ідентифікатор самої статті, в результаті пошуку цей модуль поверне ключ за допомогою якого фронт вже сходить до модулю `Article` і забере повну інформацію, щоб відрендерити сторінку.

## SharedKernel
>Проксі та збірник коду, який можуть використовувати модулі будь-яких контекстів.

[**AggregateRoot**](src/SharedKernel/Aggregate/AggregateRoot.php) - 
Клас, який дозволяє своїм підклассам (**root aggregate**) створювати доменні івенти.

[**DomainEventInterface**](src/SharedKernel/Event/DomainEventInterface.php) - 
Інтерфейс доменного івенту, який можуть створювати тільки **root aggregate**.

[**AggregateRootId**](src/SharedKernel/ValueObject/AggregateRootId.php) -
Клас, який дозволяє своїм підклассам після наслідування, виконувати роль VO-посилання на будь-який **root aggregate**. Тим самим дозволяючи агрегату в одному контексті використовувати та сворювати аліаси на агрегат іншого контексту.

>**Use Cases:**  
>`@see` [User Value Object](src/SharedKernel/ValueObject/UserValueObject.php) - абстракнтий класс який наслідує [Aggregate Root Id](src/SharedKernel/ValueObject/AggregateRootId.php). І тепер модуль `User` контексту `Auth` використовується в модулі `Article` контексту `Blog` під аліасом [AuthorId](src/Blog/Article/Domain/ValueObject/AuthorId.php) в [Root Aggregate Article](src/Blog/Article/Domain/Entity/Article.php)

**P.S. Всі аліаси, створені з цих VO, позбавлені можливості генерувати uuid.**

## Навіщо це все?
Бізнес буває маленьким середнім та великим. Коли ми говоримо про великий бізнес, то тут все зрозуміло - багато контекстів: магазин, склад, служба доставки, бугалтерія і багато всього іншого. Але навіщо це все стартапам та середнім бізнесам?  
Бо всі великі бізнеси починали з маленького. Ніхто з них навіть не уявляв, що в майбутньому вони стануть корпорацією з мільярдним оборотом в рік.  

Переваги:
- Кожен контекст може мати свою группу розробників, які будуть розмовляти та писати код на одному `Ubiquitous Language`. Це дозволить розуміти експертів в області та швидше розуміти замовників та робити `onboarding`. Бо не буде такого що на сайті в блозі автор називається `Author`, а в коді - `User` або `Customer`.
- Кожна группа працює над одним контекстом, при цьому не торкаючись інших контекстів над якими працюють інші розробники.
- Можливість легкого переходу на мікросервіси.

Недоліки:
- Високий поріг входження (можливо це навіть плюс).
- Повільна стартова швидкість розробки.
- Переускладнені зв'язки між різніми сутностями, та різні блокуючі фактори, наприклад один модуль не може напряму використовувати інший.

## Aggregates
Всі операції на агрегаті повинні бути атомарні і гарантувати, що після збереження агрегату всі його частини не будуть **inconsistency**.  
Але з цього виходить інша проблема, для того, щоб зберегти весь агрегат в одній транзакції потрібно завантажити всі його частини в пам'ять, зробити зміну, та зберегти в одній транзакції.
Тож агрегати повинні притримуватись ACID:
>**A - Atomic**  
>Всі зміни кластеру агрегату повинні відбуватися через **root aggregate**. Наприклад додавання `Comment` до `Article` повинно відбуватися через `Article` тільки через нього.

>**C - Consistency**  
>Описує, що агрегат може мати в собі посилання на інші **root aggregates** та сам агрегат може використовуватись в інших **root aggregates**.  
> Тому всі зміни в агрегат мають відбуватися в рамках однієї транзакції. Це означає, наприклад при видаленні `Article` потрібно щоб в модулі пошуку та картинок видалилися записи пов'язані з ним.  
> В книжковому світі це може і так. Але в реальності це призведе до дуже великого агрегату, який буде налазити на інші `Bounded Contexts`, притягне залежності в модуль-ініціатора ну і також це призведе до створення конкурентних запросів в бд.  
> Для цього можна використати `Message Bus` та `Event Dispatcher`, використання цих компонентів дозволить достигнути **consistency** данних не відразу, але в кінці ця **consistency** буде достигнута.
Може пройти 1 мілісекунда, година, але данні між агрегатами 100% будуть **consistency** через деякий час.

>**I - Isolation**  
>Може відбутися так ситуація, що одночасно прийло 2 запити на зміну аггрегату. 1 запит зберіг данні раніше 2 запиту, а 2 запит перезаписав данні 1 запиту. Це все призводить до **inconsistency** данних. Щоб це пофіксити можна в таблиці тримати версію данних і перед записом до бд перевіряти чи версія при доставанні данних = версії в бд при зберіганні. Або можна використовувати режим транзакцій різного рівня.

>**D - Durability**  
Правило описує, що данні повинні бути довговічними та не пропадати просто так. Для цього робимо бекапи бд.

>Якщо мікросервіс може поміститися в голові одного розробника - це хороший мікросервіс.  
> _Конфуций, 479 рік до н. є._
