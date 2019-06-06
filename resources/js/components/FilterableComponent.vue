<template>
  <div class="container">
    </br>
    </br>
    <div class="card">
      <div class="card-header">
        <div class="row d-flex justify-content-between d-flex align-items-center">
          <h4>Employee Search</h4>
          <div class="d-flex align-items-center match-options">
            <span>Match</span>
            <select class="form-control form-control-sm" v-model='query.match'>
              <option selected value="all">All</option>
              <option value="any">Any</option>
            </select>
            <span>filters</span>
          </div>
        </div>
      </div> <!-- Card head -->
      <div class="card-body">
      <filter-option :fields='fields' :filter='f' :index='i' v-for='(f, i) in filterCandidates'></filter-option>
        <div class="form-row">
          <div><button class="btn btn-success btn-sm" @click='addFilter'>Add Filter</button></div>
          <div><button class="btn btn-primary btn-sm" @click='update'>Search</button></div>
        </div>
      </div><!-- Card body -->
    </div>
    </br>
    <div class="card table-card">
      <table class="table table-hover table-sm">
        <thead class=""><th v-for='(field, i) in fields'>{{field.title}}</th></thead>
        <tbody>
          <tr v-for='(row, i) in collection.data'>
            <td v-for='(field, i) in fields'>{{ row[field['name']] }}</td>
          </tr>
        </tbody>
      </table>
    <div class="changePageSection">
      <div><button class="btn btn-secondary btn-sm" @click="prevPage">Previous</button></div>
      <div><button class="btn btn-secondary btn-sm" @click="nextPage">Next</button></div>
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
        loading: false,
        url: 'api/customers',
        filterCandidates: [],
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
        const filters = this.getFilters();
        const params = { ...filters, ...this.query }

        axios.get(this.url, {params: params})
          .then((res) => {

            Vue.set(this.$data, 'collection', res.data.collection)
            this.query.current_page = res.data.collection.current_page;

          })
          .catch((error) => {})
          .finally(() => {
            this.loading = false
        })
       },
       getFilters() {
          const f = {}

          this.filterCandidates.forEach((filter, i) => {
            if (filter.column.name && filter.operator.name && filter.value_1) {
              f[`f[${i}][column]`] = filter.column.name
              f[`f[${i}][operator]`] = filter.operator.name
              f[`f[${i}][value_1]`] = filter.value_1
              f[`f[${i}][value_2]`] = filter.value_2
              f[`f[${i}][match]`] = this.match
            }  else { return [] }
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
      this.$on('delete-Filter', function(index) {
        this.$delete(this.filterCandidates, index)
      })
      this.fetch();
      this.addFilter();
    }
  }
</script>

<style scoped>
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

.form-row div {
  margin-right: 5px;
}


.changePageSection {
  margin-top: 10px;
  display: flex;
  justify-content: space-between;
}

.changePageSection div:nth-child(3) {
  flex:1;
}

.countSelect {

  margin-left: 5px;
}




</style>
