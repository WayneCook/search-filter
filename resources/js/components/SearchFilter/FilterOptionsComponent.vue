<template>
  <form>
    <div class="row">
      <div class="col-4">
        <div class="form-group">
            <v-select
              label='Filter by'
              :items='fields'
              :item-value='fields.value'
              @input='setColumn($event)'
              :error-messages='getError(`f.${index}.column`)'
              color='purple'
            ></v-select> 
        </div>
      </div>

      <!-- Operator -->
      <div class="col-4">
        <div class="form-group">
          <v-select
            :items="getOperators"
            label="Filter by"
            :item-value="getOperators.value"
            @input='setOperator($event)'
            :error-messages="getError(`f.${index}.operator`)" 
            color='purple'
            v-if='this.filter.column'
          ></v-select>
        </div>    
      </div> <!-- End Operator -->

      <!-- Value_1 -->
      <div class="col">
        <v-text-field
          v-model='filter.value_1' 
          label="Regular"
          :error-messages="getError(`f.${index}.value_1`)"
          color='purple'
          v-if='this.filter.column'
          ></v-text-field>
      </div>

      <!-- Value_2 -->
      <div class="col" v-show='filter.operator.component === "double"'>
        <v-text-field
          v-model='filter.value_2' 
          label="Regular"
          :error-messages="getError(`f.${index}.value_2`)"
          color='purple'
        ></v-text-field>
      </div>


      <v-btn fab dark small color="normal" @click='deleteFilter()' v-if='this.filter.column'>
      <v-icon dark>remove</v-icon>
    </v-btn>


    </div>
  </form>
</template>

<script>


export default {

  props: ['filter', 'fields', 'index', 'filterErrors'],

  methods: {
    setColumn(e) {

      this.fields.forEach((column) => {
        if (column.value === e) { this.filter.column = column }
      });

    },
    setOperator(e) {
  
      this.availableOperators().forEach((operator) => {
        if (operator.value === e) {
          this.filter.operator = operator;
        }
      });
    },
    deleteFilter() {

      this.$eventHub.$emit('delete-filter', this.index);

    }, 
    availableOperators() {
      return [
          {text: 'contains', value: 'contains', parent: ['string'], component: 'single'},
          {text: 'starts with', value: 'starts_with', parent: ['string'], component: 'single'},
          {text: 'ends with', value: 'ends_with', parent: ['string'], component: 'single'},

          {text: 'equal to', value: 'equal_to', parent: ['numeric', 'string'], component: 'single'},
          {text: 'not equal to', value: 'not_equal_to', parent: ['numeric', 'string'], component: 'single'},
          {text: 'less than', value: 'less_than', parent: ['numeric'], component: 'single'},
          {text: 'greater than', value: 'greater_than', parent: ['numeric'], component: 'single'},

          {text: 'between', value: 'between', parent: ['numeric'], component: 'double'},
          {text: 'not between', value: 'not_between', parent: ['numeric'], component: 'double'},

          {text: 'in the past', value: 'in_the_past', parent: ['datetime'], component: 'datetime_1'},
          {text: 'in the next', value: 'in_the_next', parent: ['datetime'], component: 'datetime_1'},
          {text: 'in the peroid', value: 'in_the_peroid', parent: ['datetime'], component: 'datetime_2'},

          {text: 'equal to', value: 'equal_to_count', parent: ['counter'], component: 'single'},
          {text: 'not equal to', value: 'not_equal_to_count', parent: ['counter'], component: 'single'},
          {text: 'less than', value: 'less_than_count', parent: ['counter'], component: 'single'},
          {text: 'greater than', value: 'greater_than_count', parent: ['counter'], component: 'single'},
      ]
    },
    getError(input) {
      return this.filterErrors[input] ? this.filterErrors[input][0] : '';
    }
  },
  computed: {
    getOperators() {
      return this.availableOperators().filter((eachOperator) => {
        return (eachOperator['parent'].includes(this.filter.column.type))
      })
    },

  }
}
</script>

<style scoped>

  i.fas.fa-times {
    font-size: 13px;
    color: #f44336;
    margin-right: 8px;
    margin-right: 8px;
    margin-top: 7px;
  }

  .v-btn--active, .v-btn:focus, .v-btn:hover {
    border: none;
    outline: none;
}



</style>
