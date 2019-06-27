<template>
  <div class="container">
    <br>
    <br>

    <v-card>

      <v-card-title primary-title>

        <div class="headline"><v-icon class="purple myIcon" large dark>search</v-icon>Employee Search</div>
       
      </v-card-title>


<!-- 
      <div>
        <div class="d-flex justify-content-between d-flex align-items-center">
          <h4>Employee Search</h4>
          <div class="d-flex align-items-center match-options">
            <span>Match</span>
            <select class="form-control form-control-sm match-select" v-model='query.match'>
              <option selected value="all">All</option>
              <option value="any">Any</option>
            </select>
            <span>filters</span>
          </div>
        </div>
      </div> Card head -->


      <div class="card-body">
      <filter-option :filterErrors='errors' :fields='fields' v-bind:key='i' :filter='f' :index='i' v-for='(f, i) in filterCandidates'></filter-option>
        <div class="form-row">
          <!-- <div><button class="btn btn-success btn-md" @click='addFilter'>Add Filter</button></div> -->
          <!-- <div><v-btn small color="success" @click='addFilter'>add filter</v-btn></div> -->
          <v-btn fab small dark color="teal" @click='addFilter'>
            <v-icon dark>add</v-icon>
          </v-btn>



          <!-- <div><v-btn small color="purple" @click='update'>Search</v-btn></div> -->

          <v-btn fab small dark color="purple" @click='update'>
            <v-icon dark>search</v-icon>
          </v-btn>

          <!-- <div><button v-show='searchIsReady' class="btn btn-primary btn-md" @click='update'>Search</button></div> -->
        </div>
      </div><!-- Card body -->
    </v-card>

    <br>


    <div class="card table-card">
      <table class="table table-hover table-sm">
        <thead class=""><th v-bind:key='i' v-for='(field, i) in fields'>{{field.text}}</th></thead>
        <tbody>
          <tr v-bind:key='i' v-for='(row, i) in collection.data'>
            <td v-bind:key='i' v-for='(field, i) in fields'>{{ row[field['value']] }}</td>
          </tr>
        </tbody>
      </table>
    <div class="changePageSection">
      <div>
      <button class="btn btn-secondary btn-md" @click="prevPage">Previous</button>
      <button class="btn btn-secondary btn-md" @click="nextPage">Next</button>
      </div>
      <div><small> Showing {{collection.from}} - {{collection.to}} of {{collection.total}} entries.</small></div>
      <div class="perPageSelect">
        <div class="form-group form-inline">
          <small for="countSelect">Show per page</small>
          <select class="form-control form-control-sm countSelect" name='countSelect' @change='fetch()' v-model='query.limit'>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
      </div>
    </div>
  </div><!-- Table -->

  </div>
</template>

<script>
  import filterOption from './filterOptionsComponent.vue';

  export default {

    props: ['fields'],
    components: { filterOption },
    data() {
      return {
        selectValue: '',
        searchIsReady: true,
        loading: false,
        url: 'api/customers',
        filterCandidates: [],
        errors: {
     
        },
        query: {
          order_column: 'first_name',
          order_direction: 'asc',
          match: 'all',
          limit: 10,
          page: 1,
        },
        collection: {
            data: []
        }
      }
    },
    methods: {

      update() {
        this.fetch();
      },
      nextPage() {
        if(this.collection.next_page_url) {
               this.query.page = Number(this.query.page) + 1
               this.fetch();
           }
       },
       prevPage() {
         if(this.collection.prev_page_url) {
                this.query.page = Number(this.query.page) - 1
                this.fetch();
            }
       },
       addFilter() {
        this.filterCandidates.push({
          column: '',
          operator: '',
          value_1: '',
          value_2: ''
        })
       },
       fetch() {
        this.loading = true;
        this.errors = {};
        const filters = this.getFilters();
        const params = { ...filters, ...this.query }

        axios.get(this.url, {params: params})
          .then((res) => {

            console.log(this.filterCandidates);
            Vue.set(this.$data, 'collection', res.data.collection);
            this.query.current_page = res.data.collection.current_page;

          })
          .catch((error) => {

            if (error.response) {
              console.log(error.response.data.errors);
              this.errors = error.response.data.errors;
            }
          })
          .finally(() => {
            this.loading = false
        })
       },
       getFilters() {
          const f = {}

          this.filterCandidates.forEach((filter, i) => {
            if (filter.column.value) {
              f[`f[${i}][column]`] = filter.column.value
              f[`f[${i}][operator]`] = filter.operator.value
              f[`f[${i}][value_1]`] = filter.value_1
              f[`f[${i}][value_2]`] = filter.value_2
              f[`f[${i}][match]`] = this.match
            }
          })
        return f
      }
    },
    computed: {
      pageCount() {
        let l = this.query.total_records,
            s = this.query.limit;
        return Math.ceil(l/s);
      }
    },
    mounted() {

      this.$eventHub.$on('delete-filter', index => {        
        this.$delete(this.filterCandidates, index)

      });

      this.fetch();
      this.addFilter();

    },

  }
</script>

<style scoped>

.container {
  max-width: 1200px;
}

.profileImage {
  width: 40px;
}

h4 {
  margin-bottom: 0px;
  margin-left: 16px;
}

.match-options span {
  margin: 5px;
}

.table-card {
  padding: 20px;
}

.table th {
  border-top: 0px;
}

.table tbody td {
  color: rgb(99, 98, 98);
  font-size: 14px;
}

.changePageSection div {
  margin-right: 5px;
}

.form-row {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}

.v-btn--active, .v-btn:focus, .v-btn:hover {
    border: none;
    outline: none;
}


.changePageSection {
  margin-top: 10px;
  display: flex;
  justify-content: space-between;
}



.countSelect {

  margin-left: 5px;
}

.match-select {
  display: inline-block;
  width: auto;
  vertical-align: middle;
}


.myIcon {
  padding: 14px 14px;
  border-radius: 4px;
  box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(207, 30, 233, 0.4);
  margin-right: 14px;
}

.headline {
    font-size: 26px!important;
    line-height: 32px!important;
    font-weight: 300;
    color: #797679;
}


</style>
