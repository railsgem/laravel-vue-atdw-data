<template>
    <div class="container" style="margin-top:50px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><strong> Laravel Vue JS Infinite Scroll</strong></div>

                    <div class="card-body">
                        <div>
                          <p v-for="item in list">
                            <a v-bind:href="item.productId" target="_blank">{{item. productNumber + ' - '+ item.productName}}</a>
                          </p>
                          <infinite-loading infinite-scroll-disabled="busy" @distance="1" @infinite="infiniteHandler"></infinite-loading>

                        <span v-if="noMoreData"> no more data</span>
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
            console.log('Component mounted.')
        },
        data() {
            return {
              list: [],
              page: 1,
              size: 10,
              region: 'sydney',
              area: 'sydney',
            };
          },
          methods: {
            infiniteHandler($state) {
                let vm = this;
                var randSeed = new Date().getFullYear();

                var requestParams = {
                    pge: this.page,
                    size: this.size,
                    order: randSeed
                };
                if (this.region != null) {
                    requestParams['rg'] = this.region;
                }
                if (this.area != null) {
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
