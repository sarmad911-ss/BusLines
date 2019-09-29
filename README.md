# Laravel starter pack

## Modules:

### Auth
Existing tables: `users, user_roles, forgot_requests` <br/>
Default admin: `superadmin : adminsuper` <br/>
Default user: `isf.common@gmail.com : testuser` <br/>

### Files
Existing tables: `files`

By default files saved to /storage/app/public/uploads/


#### - Attributes

| Name | Type | Description |
|------|------| ------ |
| path | string | File location in `/storage/app/public/uploads/` . Default `/` |
| name | string | File name with extension |
| size | integer | File size |

#### - Setters

`file` - type UploadedFile, file from request.

Files was saved on disc by setting `file` attribute.

#### - Getters

`src` - file url without domain
`url` - file url with domain
`full_path` - file path strated from `/storage/app/...`

#### - Methods

`function getList($path)` - return files and directories in `$path`

`function rename($name)` - rename file in table and on the disk

`function delete()` - remove file from the disk and database

`function createDir($path)` - make new directory inside the `$path`

`function watermarkPhoto($text)` - return image with `$text` in left bottom corner, resize image to `$defaultWidth` (private attribute)

### Meta Tags
```php
use App\Presentors\MetaTags;
 
...

$tags = new MetaTags(['title'=>"Test","description" => "sdfsadf"]);
$data['meta'] = $tags->generate();
```

| Param | Type | Default |
| ------ | ------ | ------ |
| title | `string` | empty |
| description | `string` | empty |
| keywords | `string` | empty |
| image | `App\Models\File \| string` | empty |
| url | `string` | `url()->current()` |
| og_title | `string` | title |
| og_description | `string` | description |
| og_image | `App\Models\File \| string` | image |
| og_url | `string` | url |
| og_site_name | `string` | empty |
| twitter_card | `string` | empty |


### Pagination
Customization:
Your paginator can be customize \resources\views\vendor\pagination\.

Usage:
Create paginator from QueryBuilder object: 
```php 

...
$users = User::where('archived',false)->paginate($perPage = 15, $page = null, $options = []);
...

```

Create paginator from Collection:
```php 

...
$users = User::all()->paginate($perPage = 15, $page = null, $options = []);
...

```
Use in html:
Paginator on view can be user like collection of objects:
```html
...
@foreach($users as $user)
    <?=$user->name?>
    <?=$user->email?>
@endforeach

...
```
To show pages of paginator use links():
```html
...
<?=$users->links()?>

...
```

### Browscap
Usage:
Edit .env to choose configs:

.env

...

BLOCK_CHROME=false
BLOCK_FIREFOX=false
BLOCK_SAFARI=false
BLOCK_OPERA=false
BLOCK_MOBILE=false
BLOCK_TAB=false

Error: 
If you have error "there is no active cache available, please use the BrowscapUpdater and run the update command":

Remove folder \storage\framework\cache\browscap and use "php artisan browscap:update"

### Social Authorization

For authorization with facebook or google you may use our JS libraries.

for get user data just rewrite `onLogin` method in object as show below

#### Facebook
```js
window.FBAuth = require('./components/FBLogin');
facebook = new FBAuth('facebook_client_id');
facebook.init();
facebook.onLogin = function(data) {...};
```

`<button onclick="facebook.login()">Facebook</button>`

#### Google
```js
window.Google = require('./components/GoogleLogin');
google = new Google('google_client_id');
google.init();
google.onLogin = function(BasicProfile){...};
```
`<button onclick="google.login()">Google</button>`

### Social sharing

#### Facebook

`shareFacebook($url = '')` - function to share url in facebook. By default current url.

#### Twitter

`share_tweeter($text,$url="",$hashtags="")` - function to share url in tweeter. Url by default current url.

#### LinkedIn

`share_linkedIn($title,$url="",$description = "",$source = '')` - function to share url in LinkedIn. Url by default current url.

#### Telegram

`share_telegram($url="",$text="")` - function to share url in Telegram. Url by default current url. Text - text in message.

### Slug

Slug generate automatically in `slug` attribute setter. 

By default from `slug` field, if `slug` field is empty - from `name` field.

You can change this by setting protected property `slugFromField`.

If you need automatically slug generate in other setters - just add code bellow to end of your setter:

```php
    function setNameAttribute($name){
        $this->attributes['name'] = $name;
        $this->generateSlug();              //Add this line
    }
```

Function `generateSlug($attribute,$force)` check current slug, if not empty - check if unique, make it unique and save.

If current slug is empty - generate from attribute in first param or in `slugFromField` attribute if param is empty.

If force setted to `true` - it skip slug check and force generate from attribute.


## Libraries:
*  [ivanlemeshev/laravel5-cyrillic-slug](https://github.com/ivanlemeshev/laravel5-cyrillic-slug)

### ivanlemeshev/laravel5-cyrillic-slug
Usage: 
`(new Slug())->make($text)`

### davejamesmiller/laravel-breadcrumbs
https://github.com/davejamesmiller/laravel-breadcrumbs <br/>
file: `routes/breadcrumbs.php`


## tiny-date-picker
*  [tiny-date-picker](https://github.com/chrisdavies/tiny-date-picker)

Usage: 
Add to input class `date-picker-input`

*  [text-editor](https://alex-d.github.io/Trumbowyg/documentation/)

## text-editor
Usage: 
Add to div class `custorEditor` and add id which ll be textarea name

## custom-select
Usage: 
Add to select class `custorEditor`

| Param | Type | Default |
| ------ | ------ | ------ |
| maxActiveItems | `boolean` | false |
| columns | `integer` | 1 |
| multipleItems | `boolean` | false |

>HTML example of DOM element

```html
<select name="testSelect" class="customSelect">
    <option value="1">test1</option>
    <option value="2">test2</option>
    <option value="3">test3</option>
    <option value="4">test4</option>
</select>
```

>JS example of initializing

```js
$('.customSelect').customSelect({});
```