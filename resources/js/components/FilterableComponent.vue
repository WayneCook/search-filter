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
      </div>

      <div class="card-body">

      <filter-option :fields='fields' :filter='f' :index='i' v-for='(f, i) in filterCandidates'></filter-option>

        <div class="form-row">
          <div class="col-auto">
            <button class="btn btn-success btn-sm" @click='addFilter'>Add Filter</button>
          </div>
          <div class="col-auto">
            <button class="btn btn-primary btn-sm" @click='update'>Search</button>
          </div>
        </div>
      </div>
    </div>
    </br>
    <div class="card table-card">

      <table class="table table-hover table-sm">
        <thead class="">
          <th v-for='(field, i) in fields'>{{field.title}}</th>
        </thead>
        <tbody>

        <tr v-for='(row, i) in collection.data'>

          <td v-for='(field, i) in fields'>{{ row[field['name']] }}</td>

        </tr>
      </tbody>

      </table>

      </div>

      <button class="btn btn-secondary btn-sm" @click="prevPage">Previous</button>
      <button class="btn btn-secondary btn-sm" @click="nextPage">Next</button>
      <br>
      <!-- <p>Showing page {{ computedPageNumber }} of {{ pageCount }} of {{ data.length }} entries. </p> -->
  </div>
</template>

<script>
  import filterOption from './filterOptionsComponent.vue';

  export default {

    props: ['fields'],
    components: { filterOption },
    data() {
      return {
        data: '',
        loading: false,
        url: 'api/customers',
        filterCandidates: [],
        pageNumber: 0,
        size: 10,
        query: {
                order_column: 'first_name',
                order_direction: 'asc',
                match: 'all',
                limit: 10,
                page: 1
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
          this.query.page++;
          this.fetch();
       },
       prevPage() {
         this.query.page--;
         this.fetch();

       },
       addFilter() {
        this.filterCandidates.push({
          column: '',
          operator: '',
          value_1: ''
        })
       },
       fetch() {
        this.loading = true;
        const filters = this.getFilters();
        const params = { ...filters, ...this.query }

        axios.get(this.url, {params: params})
          .then((res) => {

            this.query.page = res.data.collection.current_page;
            this.collection.data = res.data.collection.data;
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
              f[`f[${i}][match]`] = this.match
            }  else { return [] }
          })
        return f
      }
    },
    computed: {
      pageCount() {
            let l = this.data.length,
                s = this.size;
            return Math.ceil(l/s);
      },
      computedPageNumber() {
        return (this.data.length < 1) ? 0 :this.pageNumber + 1;
      }
    },
    mounted() {
      this.$on('delete-Filter', function(index) {
        this.$delete(this.filterCandidates, index)
      })

      this.fetch();
      this.addFilter();
    },
    created() {

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


</style>
