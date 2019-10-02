import axios from 'axios';

export default {
    props: ['status_arr', 'country_arr', 'property_type', "properties"],
    data() {
        return {
            searchRegion: null,
            searchType: null,
            searchStatus: null,
            searchTitle: '',
            searchDescription: '',
            searchBathroom: '',
            searchBedroom: '',
            searchRent: null,
            searchSale: null,
            buildings: {},
            statuses: [],
            countries: [],
            types: [],
            pageArr: [],
            fieldName: "",
            maxPages: 1,
            linksCountOnEachSide: 5,
            orderByType: 'DESC',
            currentPage: 1,
        }
    },
    methods: {

        getResults(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            if(page < 1 || page > this.buildings.total) return;
            axios.get('/get_buildings?page=' + page,{
                params: {
                    region_id: this.searchRegion,
                    search_type: this.searchType,
                    status_id: this.searchStatus,
                    search_title: this.searchTitle,
                    search_description: this.searchDescription,
                    search_bathroom: this.searchBathroom,
                    search_bedroom: this.searchBedroom,
                    search_rent: this.searchRent,
                    search_sale: this.searchSale,
                }
            })
               .then(res => {
                    this.currentPage = page;
                    this.createPagination();
                    this.buildings = res.data;
                });
        },
        searchProperty() {
            axios.get("/get_buildings", {
                params: {
                    region_id: this.searchRegion,
                    search_type: this.searchType,
                    status_id: this.searchStatus,
                    search_title: this.searchTitle,
                    search_description: this.searchDescription,
                    search_bathroom: this.searchBathroom,
                    search_bedroom: this.searchBedroom,
                    search_rent: this.searchRent,
                    search_sale: this.searchSale,
                }
            }).then(res => {
                this.currentPage = 1;
                this.buildings = res.data;
                this.createPagination();
            }).catch(e => {
            })
        },
        createPagination(){
            let pages = this.buildings.total;
            this.pageArr = [];
            let firstPage = this.currentPage - this.linksCountOnEachSide;
            if(firstPage < 1) firstPage = 1;
            for(let i = 0; i < this.linksCountOnEachSide * 2 + 1; i ++){
                this.pageArr.push(firstPage + i);
                if(firstPage + i >= pages) break;
            }
        },
        filter(fieldName, event) {
            if (this.orderByType === "DESC" && fieldName === this.fieldName) {
                this.orderByType = "ASC";
            } else {
                this.orderByType = "DESC";
            }
            axios.get("/get_buildings", {
                params: {
                    orderByType: this.orderByType,
                    fieldName: fieldName,
                    region_id: this.searchRegion,
                    search_type: this.searchType,
                    status_id: this.searchStatus,
                    search_title: this.searchTitle,
                    search_description: this.searchDescription,
                    search_bathroom: this.searchBathroom,
                    search_bedroom: this.searchBedroom,
                    search_rent: this.searchRent,
                    search_sale: this.searchSale,
                }
            }).then(res => {
                let elements = document.querySelectorAll(".carets");
                for (let i = 0; i < elements.length; i++) {
                    let classList = elements[i].classList;
                    if (!classList.contains("caret")) {
                        classList.add("caret");
                    }
                    if (!classList.contains("caretup")) {
                        classList.add("caretup");
                    }
                }
                if (this.orderByType === "ASC" && this.fieldName === fieldName) {
                    event.target.querySelector(".carets").classList.remove("caret");
                } else {
                    event.target.querySelector(".carets").classList.remove("caretup");
                }
                this.fieldName = fieldName;
                this.buildings = res.data;
                this.createPagination();
            }).catch(e => {
                console.log(e);
            })

        }
    },

    created() {
        // this.getResults();
        this.buildings = JSON.parse(this.properties);
        this.createPagination();
        console.log(this.buildings);
        this.statuses = JSON.parse(this.status_arr);
        this.countries = JSON.parse(this.country_arr);
        this.types = JSON.parse(this.property_type);
    },
    mounted() {
        console.log("test");
    }
}
