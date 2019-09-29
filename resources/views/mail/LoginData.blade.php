<table>
    <thead>
    <tr>
        <th>Данные для входа на сайнт: <a href="{{route("loginView")}}">{{route("loginView")}}</a></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Почта:</td>
        <td>{{$email}}</td>
    </tr>
    <tr>
        <td>Пароль:</td>
        <td>{{$password}}</td>
    </tr>
    </tbody>
</table>
