<template>
  <form>
    <div class="row filterContainer">
      <div class="col-4">
        <div class="form-group">
            <v-select
              label='Filter by'
              :items='fields'
              :item-value='fields.value'
              @input='setColumn($event)'
              :error-messages='getError(`f.${index}.column`)'
              color='deep-purple darken-1'
            ></v-select> 
        </div>
      </div>

      <!-- Operator -->
      <div class='col-4'>
        <div class='form-group'>
          <v-select
            :items='getOperators'
            label='Operator'
            :item-value='getOperators.value'
            @input='setOperator($event)'
            :error-messages='getError(`f.${index}.operator`)' 
            color='deep-purple darken-1'
            v-if='this.filter.column'
          ></v-select>

        </div>    
      </div> <!-- End Operator -->

      <!-- Value_1 -->
        <div class='col' v-show='filter.column.type === "datetime"'>
          <v-menu
              ref="menu1"
              v-model="date_1.menu"
              :close-on-content-click="false"
              :nudge-right="40"
              :return-value.sync="date_1.date"
              lazy
              transition="scale-transition"
              offset-y
              full-width
              min-width="290px"
          >
          <template v-slot:activator="{ on }">
            <v-text-field
                v-model="date_1.date"
                :error-messages="getError(`f.${index}.value_1`)"
                label="Picker in menu"
                prepend-icon="event"
                color='deep-purple darken-1'
                readonly
                v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="date_1.date" no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn flat color="primary" @click="date_1.menu = false">Cancel</v-btn>
            <v-btn flat color="primary" @click="$refs.menu1.save(date_1.date), setDateValue('value_1', date_1.date)">OK</v-btn>
          </v-date-picker>
        </v-menu>
      </div>

      <div class='col' v-show='filter.column.type !== "datetime"'>
        <v-text-field
          v-model='filter.value_1' 
          label='Value'
          :error-messages='getError(`f.${index}.value_1`)'
          color='deep-purple darken-1'
          v-if='this.filter.column'
          ></v-text-field>
      </div>

        <div class='col' v-show='filter.operator.component === "double"'>
          <v-menu
              ref="menu2"
              v-model="date_2.menu"
              :close-on-content-click="false"
              :nudge-right="40"
              :return-value.sync="date_2.date"
              lazy
              transition="scale-transition"
              offset-y
              full-width
              min-width="290px"         
          >
          <template v-slot:activator="{ on }">
            <v-text-field
                v-model="date_2.date"
                 :error-messages="getError(`f.${index}.value_2`)"
                label="Picker in menu"
                prepend-icon="event"
                color='deep-purple darken-1'
                readonly
                v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="date_2.date" no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn flat color="primary" @click="date_2.menu = false">Cancel</v-btn>
            <v-btn flat color="primary" @click="$refs.menu2.save(date_2.date), setDateValue('value_2', date_2.date)">OK</v-btn>
          </v-date-picker>
        </v-menu>
      </div>

      <!-- Value_2 -->
      <div class="col" v-show='filter.operator.component === "double" && filter.column.type !== "datetime"'>
        <v-text-field
          v-model='filter.value_2' 
          label="Regular"
          :error-messages="getError(`f.${index}.value_2`)"
          color='deep-purple darken-1'
        ></v-text-field>
      </div>
      <v-btn flat fab small color="normal" @click='deleteFilter()' v-if='this.filter.column'>
       <v-icon dark>delete</v-icon>
      </v-btn>
    </div>
  </form>
</template>

<script>

export default {

  props: ['filter', 'fields', 'index', 'filterErrors'],

  data() {
    return {
      date_1: {
        date: '',
        menu: ''
      },
        date_2: {
        date: '',
        menu: ''
      }
    }
  },
  methods: {
    setColumn(e) {
      this.resetFilter();

      this.fields.forEach((column) => {
        if (column.value === e) { this.filter.column = column }
      });

    },
    setDateValue(val, date) {
      this.filter[val] = date;
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
      this.removeErrors();
    }, 
    removeErrors() {
      this.filterErrors[`f.${this.index}.value_1`] = '';
      this.filterErrors[`f.${this.index}.value_2`] = '';
      this.filterErrors[`f.${this.index}.operator`] = '';
    },
    resetFilter() {
      this.filter.operator = '';
      this.filter.column = '';
      this.filter.value_1 = '';
      this.filter.value_2 = '';
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

          // Date operators
          {text: 'equal to', value: 'date_equal', parent: ['datetime'], component: 'single'},
          {text: 'before', value: 'date_before', parent: ['datetime'], component: 'single'},
          {text: 'after', value: 'date_after', parent: ['datetime'], component: 'single'},
          {text: 'between', value: 'date_between', parent: ['datetime'], component: 'double'},

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
    }
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

.filterContainer {
  margin-left: 0px;
  margin-right: 0px;
}

</style>
