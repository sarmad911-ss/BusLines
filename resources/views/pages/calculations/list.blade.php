@extends("pages.template")
@section("content")
    <div class="page-title">
        <div>Рассчеты</div>
    </div>
    <div class="custom-table">
        <div class="custom-table__head">
            <div>
                №
            </div>
            <div>
                Сумма
            </div>
            <div>
                Километраж
            </div>
            <div>
                Дата
            </div>
        </div>
        <div class="custom-table__body">
            <a class="custom-table__row" v-for="calculation in calculations" v-if="calculations.length" :href="calculation.link">
                <div class="gray-light-text">
                    @{{ calculation.id }}
                </div>
                <div>@{{ calculation.cost }}</div>
                <div>@{{ calcDistance(calculation) }}</div>
                <div>@{{ calculation.updated_at }}</div>
            </a>
            <div v-if="!calculations.length" class="empty-table">
                Нет рассчетов
            </div>
        </div>
    </div>
    <pagination :meta="meta" @ondata="onData" :params="meta"></pagination>
@endsection
@section("js")
    <script src="pages/calculationsList.js"></script>
@endsection
@section("css")
    <style>
        .custom-table .custom-table__head, .custom-table .custom-table__row{
            display: grid;
            grid-template-columns: 25% 25% 25% 25%;
        }
    </style>

@endsection
