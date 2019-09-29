@extends("pages.template")
@section("content")
	<div class="page-title">@{{ pageTitle }}</div>
	<form @submit.prevent="save">
		<div class="grid-row">
			<custom-input
					class="col__5"
					:type="'email'"
					v-model="user.email"
					:placeholder="'Mail'"
					:required="true"
					@blur="checkEmail"
					:label="'Mail'"
			></custom-input>
			<p v-if="errors && errors.email" class="error col__5">@{{errors.email[0]}}</p>
		</div>
		<div class="grid-row">
				<custom-input
						class="col__5"
						:type="'text'"
						v-model="user.name"
						:placeholder="'Name'"
						:required="true"
						:label="'Name'"
				></custom-input>
		</div>
		<div class="grid-row">
			<custom-input
					class="col__3"
					:type="'text'"
					v-model="user.phone"
					:placeholder="'Telefon'"
					:required="false"
					:label="'Telefon'"
					:not-required-text="true"
			></custom-input>
			<custom-date-time
					class="col__4 user-dob"
					v-model="user.dob"
					:placeholder="'Geburtsdatum'"
					:required="false"
					:label="'Geburtsdatum'"
					:not-required-text="true"
			></custom-date-time>
		</div>
		<div class="photo-loader">
			<img :src="user.photo_url" alt="" class="image">
			<input type="file" accept="image/*" @change="getFile" ref="loaderInput">
			<span class="link-text" @click="callPhotoInput"><i class="icon default marker-icon"></i> Foto hochladen</span>
		</div>
		<div class="grid-row ">
			<div class="col__7 user-misc">
				<div class="custom-input__label">
					Zusatzinformationen
					<span class="gray-text">nicht unbedingt notwendig</span>
				</div>
				<trumbowyg v-model="user.misc"></trumbowyg>
			</div>
		</div>
		<button class="button">Speichern</button>
	</form>
@endsection
@section("js")
	<script>user_id = {{$user->id ?? 'null'}}</script>
	<script src="{{asset("pages/users/profile.js")}}"></script>
@endsection
@section("css")
    <link rel="stylesheet" href="{{asset("css/userProfile.css")}}">
@endsection