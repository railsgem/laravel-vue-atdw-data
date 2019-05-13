<template>
    <div class="container" style="margin-top:50px;">
        <el-dialog :visible.sync="showModal" title="ProductDetails">
            <el-table :data="productDetails" v-loading="productDetailLoading">
                <el-table-column property="attribute" label="attribute"></el-table-column>
                <el-table-column property="value" label="value"></el-table-column>
            </el-table>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="showModal = false">Confirm</el-button>
            </span>
        </el-dialog>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><strong> Project List (Infinite Scroll)</strong></div>

                    <div class="card-body">
                        <form>
                            <div class="row ">
                                <div class="col-sm-4">
                                    Regions: {{selectetRegion}}
                                </div>
                                <div class="col-sm-8">
                                    <select v-model="selectetRegion" @change="changeAreaRegion">
                                        <option value="" selected> All Regions</option>
                                        <option v-for="(option, $index) in regions" :key="$index" v-bind:value="option" >
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    Areas: {{selectetArea}}
                                </div>
                                <div class="col-sm-8">
                                    <select v-model="selectetArea" @change="changeAreaRegion">
                                        <option value="" selected>All Areas</option>
                                        <option v-for="(option, $index) in areas" :key="$index" v-bind:value="option" >
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </form>
                        <el-table :data="list" height="624" border>
                            <el-table-column prop="productNumber" label="ProductNumber" width="125">
                            </el-table-column>
                            <el-table-column prop="productName" label="ProductName">
                            </el-table-column>
                            <el-table-column fixed="right" label="Operations" prop="productId" width="120">
                                <template slot-scope="scope">
                                    <el-button @click="getProductDetails(scope.$index, scope.row)" type="text" size="small">Detail</el-button>
                                </template>
                            </el-table-column>
                            <infinite-loading slot="append" :identifier="infiniteId" @distance="1" @infinite="infiniteHandler" force-use-infinite-wrapper=".el-table__body-wrapper">
                            </infinite-loading>
                        </el-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            const tableLoading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            this.fullScreenLoading = tableLoading;
            console.log('Component mounted.');
            this.fetchAreaAndRegionData();
        },
        data() {
            return {
                formLabelWidth: '120px',
                productDetailLoading: true,
                fullscreenLoading: false,
                productDetails: [],
                showModal: false,
                regions:[],
                areas:[],
                list: [],
                infiniteId: +new Date(),
                page: 1,
                size: 10,
                selectetRegion: null, //A valid region name or region id or region code.
                selectetArea: null, //A valid area name or area id or area code.
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
                        // close the full screen loading
                        vm.fullScreenLoading.close();
                    });
            },
            getProductDetails(index, row) {
                let vm = this;
                this.showModal = true;
                vm.productDetailLoading = true;
                this.$http.get('/api/product?productId=' + row.productId)
                    .then(response => {
                        return response.json();
                    }).then(data => {
                        $.each(data, function(key, value) {
                             if (value != null && typeof value == 'object') {
                                vm.productDetails.push({ attribute: key, value: JSON.stringify(value)});
                             } else {
                                vm.productDetails.push({ attribute: key, value: value});
                             }
                        });
                        vm.productDetailLoading = false;
                    });
            },
            infiniteHandler($state) {
                let vm = this;
                var today = new Date();
                var randSeed = today.getFullYear() + '-' + today.getMonth() + '-' + today.getDate();

                var requestParams = {
                    pge: this.page,
                    size: this.size,
                    order: randSeed
                };
                if (this.selectetRegion != null && this.selectetRegion != "") {
                    requestParams['rg'] = this.selectetRegion;
                }
                if (this.selectetArea != null && this.selectetArea != "") {
                    requestParams['ar'] = this.selectetArea;
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
