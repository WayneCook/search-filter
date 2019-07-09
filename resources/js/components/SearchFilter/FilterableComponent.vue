<template>
  <div class="container">

    <v-form ref="form">
      <v-card>
        <v-card-title primary-title>
          <div class="headline"><v-icon class="purple myIcon" medium dark>search</v-icon>Employee Search</div>
        </v-card-title>

        <div class="card-body">
        <filter-option :filterErrors='errors' :fields='fields' v-bind:key='i' :filter='f' :index='i' v-for='(f, i) in filterCandidates'></filter-option>
          <div class="form-row">
            <v-btn fab small dark color="teal" @click='addFilter'>
              <v-icon dark>add</v-icon>
            </v-btn>

            <v-btn fab small dark color="default" @click='reset'>
              <v-icon dark>cached</v-icon>
            </v-btn>

            <v-btn fab small dark color="purple" @click='search'>
              <v-icon dark>search</v-icon>
            </v-btn>

          </div>
        </div>
      </v-card>
    </v-form>

    <br>
      <v-data-table
        :headers='fields'
        :items='collection.data'
        :pagination.sync='table.pagination'
        class='elevation-1'
        hide-actions
        :loading='loading'
        align='left'
      >
      <template v-slot:items="props">
        <td class="text-xs-left" v-for='(header, i) in fields' :key='i'>{{ props.item[header.value] }}</td>
      </template>

      <template v-slot:footer>
        <td :colspan="fields.length">
            
          <v-layout align-center row>
            <v-subheader>Rows per page:</v-subheader>    

            <div class='rowSelectContainer'>
              
              <v-select
                  v-model='query.limit'
                  class="rowSelector text-xs-center"
                  :items='[5,10,15]'
                  :item-value='[5,10,15]'
                  color='purple'
                  width='40px'
                  @change='rowsChange()'
              ></v-select> 
            </div>
          </v-layout>
        </td>
      </template>

    </v-data-table>
    <div class="text-xs-center pt-2">
      <v-pagination 
          v-model="query.page" 
          :length="table.pagination.total"
          :total-visible='10'
          circle
          color='purple'
          @input="onPageChange"
      >
      </v-pagination>
    </div>
  </div>
</template>

<script>
  import filterOption from './FilterOptionsComponent.vue';

  export default {

    props: ['fields'],
    components: { filterOption },
    data() {
      return {
        table: {
          pagination: {
            current: 1,
            total: 0,
            rowsPerPage: 5
          }
        },
        selectValue: '',
        searchIsReady: true,
        loading: 'false',
        url: 'api/customers',
        filterCandidates: [],
        errors: {},
        query: {
          order_column: 'first_name',
          order_direction: 'asc',
          match: 'all',
          total: 0,
          limit: 5,
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
       search() {
        this.query.page = 1;
        this.fetch();
      },
       addFilter() {
        this.filterCandidates.push({
          column: '',
          operator: '',
          value_1: '',
          value_2: ''
        })
       },
       reset() {
         this.filterCandidates = [];
         this.addFilter();
         this.$refs.form.reset();
       },
       fetch() {
        this.loading = 'purple';
        this.errors = {};
        const filters = this.getFilters();
        const params = { ...filters, ...this.query }

        axios.get(this.url, {params: params})
          .then((res) => {

            Vue.set(this.$data, 'collection', res.data.collection);
            this.query.total = res.data.collection.total;
            this.table.pagination.total = res.data.collection.last_page;
            this.query.page = res.data.collection.current_page;

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
      },
      rowsChange() {
        console.log(this.query.limit);
        this.table.pagination.rowsPerPage = this.query.limit;
        this.update();
      },
      onPageChange() {
            this.fetch();
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
    }
  }
</script>

<style scoped>



.container {
  max-width: 1000px;
  /* background: linear-gradient(45deg, #009688f2, #b51889de); */
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
  padding: 12px 12px;
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

.rowSelectContainer {
  width: 20px;
}

.rowSelector {
  font-size: 12px;
}

.v-subheader {

    font-size: 12px!important;

}

</style>
