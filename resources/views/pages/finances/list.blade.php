@extends("pages.template")
@section("content")
    <div class="page-title">
        Finanzen
        <button class="button" @click="storeTransactionModal(null)">Transaktion hinzufügen</button>
    </div>
    <div class="custom-table">
        <div class="custom-table__head">
            <div>№</div>
            <div>Übertragen</div>
            <div>Summe</div>
            <div>Datum der Transaktion</div>
            <div>Beschreibung</div>
            <div>Dokument</div>
            <div>letzte Änderungen</div>
        </div>
        <div class="custom-table__body">
            <div class="custom-table__row" v-for="transaction in transactions" :key="transaction.id" @click="storeTransactionModal(transaction)">
                <div>@{{transaction.id}}</div>
                <div>@{{ transaction.purpose ? transaction.purpose.name : '' }}</div>
                <div class="cost-col">
                    @{{ transaction.cost }} <span class="gray-light-text">€</span>
                </div>
                <div>@{{ transaction.date }}</div>
                <div class="gray-text font-12">@{{transaction.misc}}</div>
                <div class="link-text font-12" v-if="transaction.file"><i class="icon empty-file-icon"></i>@{{ transaction.file.name }}</div>
                <div class="gray-light-text font-12" v-else><i class="icon empty-file-icon"></i>Ohne Dokument</div>
                <div class="gray-text font-12">
                    <div v-if="transaction.last_update.user">@{{transaction.last_update.user.name}}</div>
                    <div>@{{ transaction.last_update.time }}</div>
                </div>
            </div>
        </div>
    </div>
    <pagination v-bind:meta="meta" @ondata="onData" v-if="meta"></pagination>
    <modal v-bind:opened="storeModal" :title="modalTitle" @onclose="onModalClose">
        <transaction-form v-model="storeTransaction" @onsave="onModalClose" @onnewtransaction="onNewTransaction"></transaction-form>
    </modal>
@endsection
@section("js")
    <script src="{{asset("pages/finances.js")}}"></script>
@endsection
@section("css")
    <link rel="stylesheet" href="{{asset("css/finances.css")}}">
@endsection