@extends("pages.template")
@section("content")
	<div class="page-title">
        Benutzer
		<a href="{{route("storeUserView")}}" class="button">Benutzer hinzufügen</a>
	</div>
    <div class="users-table">
        <div class="gray-text users-table__header">
            <div class="photo-column"></div>
            <div class="id-column">№</div>
            <div class="name-column">Name</div>
            <div class="email-column">Mail</div>
            <div class="phone-column">Telefon</div>
        </div>
        <div v-for="user in users" @click="viewDetails(user.id)" :key="user.id" class="users-table__row">
            <div class="users-table__details" v-if="user.id==userDetails">
                <img class="photo" :src="`${user.photo_url}`">
                <div class="info">
                    <div class="top-line">
                        <div>
                            <span class="user-id">@{{ user.id }}</span>
                            <span class="user-name">@{{user.name}}</span>
                        </div>
                        <div class="user-links">
                            <a v-if="user.can_delete" @click="removeUser(user.id)" class="link-text gray-text"><i class="icon default marker-icon"></i>Benutzer löschen</a>
                            <a v-if="user.edit_link" :href="user.edit_link" class="link-text"><i class="icon default marker-icon"></i>Benutzer bearbeiten</a>
                        </div>
                    </div>
                    <div class="contacts-line">
                        <div class="user-email">
                            Mail <span class="black-text">@{{user.email}}</span>
                        </div>
                        <div class="contacts-line__right">
                            <div class="user-phone" v-if="user.phone">
                                Telefon <span class="black-text">@{{user.phone}}</span>
                            </div>
                            <div class="user-dob" v-if="user.dob_formatted">
                                Geburtsdatum <span class="black-text">@{{ user.dob_formatted }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="gray-text" v-if="user.misc">Zusatzinformation</div>
                    <div v-html="user.misc" class="black-text user-misc" v-if="user.misc"></div>
                    <div v-if="user.last_update" class="gray-text">
                        letzte Änderungen: <span v-if="user.last_update.user">@{{user.last_update.user.name}}</span> @{{user.last_update.time}}
                    </div>
                </div>
                <div class="expand-column"><i class="icon default expand-icon reverse"></i></div>
            </div>
            <div class="users-table__short" v-else>
                <div class="photo-column">
                    <img :src="`${user.photo_url}`">
                </div>
                <div  class="id-column gray-text">@{{user.id}}</div>
                <div class="user-name name-column">@{{user.name}}</div>
                <div  class="email-column">@{{user.email}}</div>
                <div class="phone-column">@{{user.phone}}</div>
                <div class="expand-column"><i class="icon default expand-icon"></i></div>
            </div>
        </div>
    </div>
    <pagination v-bind:meta="meta" @ondata="onData"></pagination>
    @if(session()->has("notification"))
        <notification :text="'{{session()->get("notification")}}'" v-model="showNotification"></notification>
    @endif
@endsection
@section("js")
    @if(session()->has("notification"))
    <script>
        openNotification = true;
    </script>
    @endif
	<script src="{{asset("pages/users/list.js")}}"></script>
@endsection
@section("css")
	<link rel="stylesheet" href="{{asset("css/userslist.css")}}">
@endsection
<?php session()->forget("notification"); ?>
