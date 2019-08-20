<template>
  <div class="container form" id="app">
    <!--
    <input type="file" name="file" @change="getData"/>
    <button class="btn btn-success" type="button">Get</button>
    <hr />
    -->
    <select v-model="changeForm" name="type">
      <option value="0">Insert</option>
      <option value="1">Update</option>
    </select>
    <div class="row">
      <div class="col-2">Schema Name :</div>
      <div class="col">
        <input name="schema-name" autocomplete="off" />
      </div>
    </div>
    <div class="row">
      <div class="col-2">Table Name :</div>
      <div class="col">
        <input name="table-name" autocomplete="off" />
        <button type="button" class="btn btn-outline-primary" @click="addRow" tabindex="-1">+</button>
      </div>
    </div>
    <div class="row">
      <div v-if="changeForm == 0" class="col-2">INTO :</div>
      <div v-else class="col-2">SET :</div>
      <div class="col">
        <input name="column[]" autocomplete="off" /> =
        <input name="value[]" autocomplete="off" placeholder="value" />
      </div>
    </div>
    <div v-for="(input, index) in inputs" class="row">
      <div v-if="changeForm == 0" class="col-2">INTO :</div>
      <div v-else class="col-2">SET :</div>
      <div class="col">
        <input name="column[]" autocomplete="off" /> =
        <input name="value[]" autocomplete="off" placeholder="value" />
      </div>
      <div class="col">
        <button type="button" class="btn btn-danger" @click="deleteRow(index)" tabindex="-1">Delete</button>
      </div>
    </div>
    <div v-if="changeForm > 0" class="row">
      <div class="col-2">WHERE :</div>
      <div class="col">
        <input name="condition" autocomplete="off" /> =
        <input name="condition-value" autocomplete="off" placeholder="value" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "app",
  data() {
    return {
      changeForm: 0,
      column: 0,
      inputs: [],
    };
  },
  props: ["file"],
  methods: {
    addRow() {
      this.inputs.push({
        data: ""
      });
    },
    deleteRow(index) {
      this.inputs.splice(index, 1);
    },
    getData(e) {
      console.log("Axios")
      const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = function(){
            data = reader.result;
            console.log(reader.result);
        };
        reader.readAsText(file);
      e.preventDefault();
      let currentObj = this;
      console.log(e)
      axios
        .post("/read", {
          file: file
        })
        .then(function(response) {
          currentObj.output = response.data;
        console.log(currentObj.output)
        })
        .catch(function(error) {
          currentObj.output = error;
        });
    }
  }
};
</script>

<style scoped>
.form > div {
  margin: 10px 5px;
}
.form > div > div:first-child {
  text-align: right;
}
</style>
