<template>
    <div class="container" style="margin-top:50px;">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><strong> Laravel Vue JS Infinite Scroll</strong></div>

                    <div class="card-body">
                        <form>
                            <div class="row ">
                                <div class="col-sm-4">
                                    Regions: {{region}}
                                </div>
                                <div class="col-sm-8">
                                    <select v-model="region" @change="changeAreaRegion">
                                        <option value="" selected> All Regions</option>
                                        <option v-for="(option, $index) in regions" :key="$index" v-bind:value="option" >
                                            {{ option }}
                                        </option>
                                    </select>
                                    <!-- <v-select v-model="region" :options="regions" @selected="changedLabel">></v-select> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    Areas: {{area}}
                                </div>
                                <div class="col-sm-8">
                                    <select v-model="area" @change="changeAreaRegion">
                                        <option value="" selected>All Areas</option>
                                        <option v-for="(option, $index) in areas" :key="$index" v-bind:value="option" >
                                            {{ option }}
                                        </option>
                                    </select>
                                <!-- <v-select v-model="area" :options="areas"></v-select> -->
                                </div>
                            </div>
                        </form>
                        <div>
                          <p v-for="(item, $index) in list" :key="$index">
                            <a v-bind:href="item.productId" target="_blank">{{item. productNumber + ' - '+ item.productName}}</a>
                          </p>
                          <infinite-loading :identifier="infiniteId" @distance="1" @infinite="infiniteHandler"></infinite-loading>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            this.fetchAreaAndRegionData();
        },
        data() {
            return {
                selected: null,
                regions:[],
                areas:[],
                list: [],
                infiniteId: +new Date(),
                page: 1,
                size: 10,
                region: null, //A valid region name or region id or region code.
                area: null, //A valid area name or area id or area code.
            };
          },
          methods: {
            changeAreaRegion() {
                this.page = 1;
                this.list = [];
                this.infiniteId += 1;
            },
            fetchAreaAndRegionData () {
                let vm = this;
                this.$http.get('/api/regions')
                    .then(response => {
                        return response.json();
                    }).then(data => {
                        $.each(data, function(key, value) {
                            vm.regions.push(value.Name);
                        });
                    });
                this.$http.get('/api/areas')
                    .then(response => {
                        return response.json();
                    }).then(data => {
                        console.log(data);
                        $.each(data, function(key, value) {
                            vm.areas.push(value.Name);
                        });
                    });
            },
            infiniteHandler($state) {
                let vm = this;
                var randSeed = new Date().getFullYear();

                var requestParams = {
                    pge: this.page,
                    size: this.size,
                    order: randSeed
                };
                if (this.region != null && this.region != "") {
                    requestParams['rg'] = this.region;
                }
                if (this.area != null && this.area != "") {
                    requestParams['ar'] = this.area;
                }
                //Return all listings sorting the results randomly ensuring the results are unique across all pages for the given session.
                this.$http.get('/api/products', { params: requestParams })
                    .then(response => {
                        return response.json();
                    }).then(data => {
                        console.log(data.products);
                        if (data.products.length > 0) {
                            $.each(data.products, function(key, value) {
                                    vm.list.push(value);
                            });
                            $state.loaded();
                        } else {
                            $state.complete();
                        }
                    });

                this.page = this.page + 1;
            },
          },
    }
</script>
