<template>
    <div class="container">
        <div class="row justify-content-center">


            <table class="table">
                <thead>
                <tr role="row">
                    <th @click="filter('id', $event)" class=" cursor-pointer" data-name="id" scope="col">Id<span class="caret caretup carets"></span></th>
                    <th @click="filter('title', $event)" class="sorting cursor-pointer" tabindex="0" style="width: 107px;">Title<span class="caret caretup carets"></span></th>
                    <th @click="filter('description', $event)" class="sorting cursor-pointer" style="width: 107px;">Description<span class="caret caretup carets"></span></th>
                    <th @click="filter('region_id', $event)" class="sorting cursor-pointer" style="width: 107px;">Region <span class="caret caretup carets"></span></th>
                    <th @click="filter('property_type', $event)" class="sorting cursor-pointer" style="width: 107px;">Type <span class="caret caretup carets"></span></th>
                    <th @click="filter('bathroom', $event)" class="sorting cursor-pointer" style="width: 107px;">Bathroom <span class="caret caretup carets"></span></th>
                    <th @click="filter('bedroom', $event)" class="sorting cursor-pointer" style="width: 107px;">Bedroom <span class="caret caretup carets"></span></th>
                    <th @click="filter('for_rent', $event)" class="sorting cursor-pointer" style="width: 107px;">For Rent <span class="caret caretup carets"></span></th>
                    <th @click="filter('for_sale', $event)" class="sorting cursor-pointer" style="width: 107px;">For Sale <span class="caret caretup carets"></span></th>
                    <th @click="filter('status_id', $event)" class="sorting cursor-pointer" style="width: 107px;">Status <span class="caret caretup carets"></span></th>
                <tr role="row">

                    <th rowspan="1" colspan="1" style="width: 107px;">#</th>
                    <th rowspan="1" colspan="1" style="width: 107px;"><input type="text" @keyup="searchProperty()" v-model="searchTitle" placeholder="Search Title"></th>
                    <th rowspan="1" colspan="1" style="width: 107px;"><input type="text" @keyup="searchProperty()"  v-model="searchDescription" placeholder="Search Description"></th>
                    <th rowspan="1" colspan="1" style="width: 107px;">
                        <select v-model="searchRegion" @change="searchProperty()">
                            <option value="null" disabled>Please select Region</option>
                            <option  value="">All Regions</option>
                            <optgroup v-for="country in countries" :label="country.name">
                                    <option v-for="region in country.regions" :value="region.id">{{region.name}}</option>
                                </optgroup>
                        </select>
                    </th>
                    <th rowspan="1" colspan="1" style="width: 107px;">
                        <select v-model="searchType" @change="searchProperty()">
                            <option disabled value="null">Please select Type</option>
                            <option  value="">All Type</option>
                            <option v-for="type in types" :value="type.id">{{type.name}}</option>
                        </select>
                    </th>
                    <th rowspan="1" colspan="1" style="width: 108px;"><input type="number" @keyup="searchProperty()"  v-model="searchBathroom" placeholder="Search Bathroom"></th>
                    <th rowspan="1" colspan="1" style="width: 108px;"><input type="number" @keyup="searchProperty()" v-model="searchBedroom" placeholder="Search Bedroom"></th>
                    <th rowspan="1" colspan="1" style="width: 108px;">
                        <select v-model="searchRent" @change="searchProperty()" >
                            <option disabled value="null">Please select Rent</option>
                            <option  value="">All Rents</option>
                            <option :value="1">YES</option>
                            <option :value="0">NO</option>
                        </select>
                    </th>
                    <th rowspan="1" colspan="1" style="width: 108px;">
                        <select v-model="searchSale" @change="searchProperty()" >
                            <option disabled value="null">Please select  For Sale</option>
                            <option  value="">All  Sale</option>
                            <option :value="1">YES</option>
                            <option :value="0">NO</option>
                        </select>
                    </th>
                    <th rowspan="1" colspan="1" style="width: 108px;">
                        <select v-model="searchStatus" @change="searchProperty()" >
                            <option disabled value="null">Please select Status</option>
                            <option  value="">All statuses</option>
                            <option v-for="status in statuses" :value="status.id">{{status.name}}</option>
                        </select>
                    </th>
                </tr>
                </thead>

                <tbody>
                    <tr v-for="building in buildings.data">
                        <th scope="row">{{building.id}}</th>
                        <td>{{building.title}}</td>
                        <td>{{building.description}}</td>
                        <td>{{building.region.name}}</td>
                        <td>{{building.property_type.name}}</td>
                        <td>{{building.bathroom}}</td>
                        <td>{{building.bedroom}}</td>
                        <td>{{building.for_rent ? "V" : "X"}}</td>
                        <td>{{building.for_sale ? "V" : "X"}}</td>
                        <td>{{building.status.name}}</td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item" v-bind:class="{'disabled': currentPage - 1 < 1}">
                        <a class="page-link" href="#" @click="getResults(currentPage - 1)" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li v-for="page in pageArr" v-bind:class="{'active': currentPage === page}" @click="getResults(page)" class="page-item"><a class="page-link" href="#">{{page}}</a></li>
                    <li class="page-item" v-bind:class="{'disabled': currentPage + 1 > buildings.total}">
                        <a class="page-link" href="#" @click="getResults(currentPage + 1)" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
<!--            <span class="pagination-buttons" v-for="page in pageArr" v-bind:class="{'active': currentPage === page}" @click="getResults(page)"> {{page}}</span>-->
        </div>
    </div>
</template>
<style>
    .cursor-pointer{
        cursor: pointer;
    }
    .carets{
        pointer-events:none
    }
    /* Style the input field */
    #myInput {
        padding: 20px;
        margin-top: -6px;
        border: 0;
        border-radius: 0;
        background: #f1f1f1;
    }
</style>
<script>
    import {default as code} from './dashboard.js'
    export default code
</script>
