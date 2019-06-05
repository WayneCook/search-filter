<template>


  <form>
    <div class="row">

    <div class="col">
      <div class="form-group">
        <select class="form-control form-control-sm" @input='setColumn($event)'>
        <option selected>search by</option>
        <option :value='option.name' v-for='option in this.fields'>{{ option.title }}</option>
        </select>
      </div>
    </div>

    <div class="col">
      <div class="form-group">
        <select class="form-control form-control-sm" @input='setOperator($event)'>
          <option selected>select option</option>
          <option
            v-for='operator in getOperators'
            :value='operator.name'
            >{{ operator.title }}
          </option>
        </select>
      </div>
    </div>

    <div class="col">
      <input type="text" v-model="filter.value_1" name="keyword" class="form-control form-control-sm">
    </div>

    <div class="col" v-show='filter.operator.name === "between"'>
      <input type="text" v-model="filter.value_2" name="keyword" class="form-control form-control-sm">
    </div>

    <span @click='deleteFilter()'><i class="far fa-times-circle"></i></span>
  </div>
  </form>

</template>

<script>

export default {

  props: ['filter', 'fields', 'index'],
  data() {
    return {
      //
    }
  },
    methods: {

    setColumn(e) {
        this.fields.forEach((column) => {
          if (column.name === e.target.value) { this.filter.column = column }
        });
    },
    setOperator(e) {
        this.availableOperators().forEach((operator) => {
          if (operator.name === e.target.value) { this.filter.operator = operator }
        });

        console.log(this.filter.operator);
    },
    deleteFilter() {
      this.$parent.$emit('delete-Filter', this.index);
    },
  fetch() {

    this.loading = true
    const filters = this.getFilters()

    const params = {
        ...filters,
        ...this.query
    }
    axios.get(this.url, {params: params})
      .then((res) => {
          Vue.set(this.$data, 'collection', res.data.collection)
          this.query.page = res.data.collection.current_page
      })
      .catch((error) => {

      })
      .finally(() => {
          this.loading = false
      })
    },
    availableOperators() {
      return [
          {title: 'contains', name: 'contains', parent: ['string'], component: 'single'},
          {title: 'starts with', name: 'starts_with', parent: ['string'], component: 'single'},
          {title: 'ends with', name: 'ends_with', parent: ['string'], component: 'single'},

          {title: 'equal to', name: 'equal_to', parent: ['numeric', 'string'], component: 'single'},
          {title: 'not equal to', name: 'not_equal_to', parent: ['numeric', 'string'], component: 'single'},
          {title: 'less than', name: 'less_than', parent: ['numeric'], component: 'single'},
          {title: 'greater than', name: 'greater_than', parent: ['numeric'], component: 'single'},

          {title: 'between', name: 'between', parent: ['numeric'], component: 'double'},
          {title: 'not between', name: 'not_between', parent: ['numeric'], component: 'double'},


          {title: 'in the past', name: 'in_the_past', parent: ['datetime'], component: 'datetime_1'},
          {title: 'in the next', name: 'in_the_next', parent: ['datetime'], component: 'datetime_1'},
          {title: 'in the peroid', name: 'in_the_peroid', parent: ['datetime'], component: 'datetime_2'},

          {title: 'equal to', name: 'equal_to_count', parent: ['counter'], component: 'single'},
          {title: 'not equal to', name: 'not_equal_to_count', parent: ['counter'], component: 'single'},
          {title: 'less than', name: 'less_than_count', parent: ['counter'], component: 'single'},
          {title: 'greater than', name: 'greater_than_count', parent: ['counter'], component: 'single'},
      ]
    }
  },
  computed: {

    getOperators() {
      return this.availableOperators().filter((eachOperator) => {
        return (eachOperator['parent'].includes(this.filter.column.type))
      })
    },
    fetchOperators() {
      return (f) => {
        return this.availableOperators().filter((operator) => {
            if(f.column && operator.parent.includes(f.column.type)) { return operator }
        })
      }
    }
  },
  watch: {
    filter: {
     immediate: false,
     deep: true,
     handler(newValue, oldValue) {
       // console.log('changed');
     }
    }
  }
}
</script>

<style scoped>

i.far.fa-times-circle {
    font-size: 13px;
    color: #f44336;
    margin-right: 8px;
    margin-right: 8px;
    margin-top: 7px;
}


</style>
