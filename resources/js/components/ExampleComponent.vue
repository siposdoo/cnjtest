<template>
  <div>
    <b-container class="mt-3">
      <b-row>
        <b-col>
          <b-row>
            <b-col>
              <b-form-file
                v-model="csvFile"
                :state="Boolean(csvFile)"
                placeholder="Choose a CSV file for proccessing or drop it here..."
                drop-placeholder="Drop file here..."
              >
              </b-form-file>
            </b-col>
          </b-row>
          <b-row v-if="csvFile" align-v="end">
            <b-col>
              <div>
                <i>Selected file: {{ csvFile ? csvFile.name : "" }}</i>
              </div>
            </b-col>
          </b-row>
          <b-row v-if="csvFile" align-v="end">
            <b-col>
              <b-form-checkbox
                value="1"
                unchecked-value="0"
                v-model="saveToMysql"
              >
                Save to MySql
              </b-form-checkbox>
            </b-col>

            <b-col class="d-flex flex-row-reverse bd-highlight">
              <button class="mt-3 btn btn-primary" v-on:click="submitFile()">
                Submit & parse CSV
              </button>
            </b-col>
          </b-row>
          <b-row v-if="showErr" class="mt-3 w-100">
            <b-alert dismissible variant="danger" :show="showErr">{{
              contentErr
            }}</b-alert>
          </b-row>
          <b-row v-if="showSuccess" class="mt-3 w-100">
            <b-alert dismissible variant="success" :show="showSuccess">{{
              contentSuccess
            }}</b-alert>
          </b-row>
          <b-row v-if="showSuccess" class="mt-1">
            <ul class="list-group w-100">
              <li class="list-group-item active">Avg. price:</li>
              <li class="list-group-item ">{{ avgPrice }}</li>
            </ul>
          </b-row>
          <b-row v-if="showSuccess" class="mt-1">
            <ul class="list-group w-100">
              <li class="list-group-item active">Total houses sold:</li>
              <li class="list-group-item ">{{ totalHouseSold }}</li>
            </ul>
          </b-row>
          <b-row v-if="showSuccess" class="mt-1">
            <ul class="list-group w-100">
              <li class="list-group-item active">Number of crimes in 2011:</li>
              <li class="list-group-item ">{{ numberOfCrimes }}</li>
            </ul>
          </b-row>
          <b-row v-if="showSuccess" class="mt-3">
            <ul class="list-group w-100">
              <li class="list-group-item active">
                Avg price per year in London area:
              </li>
              <li
                v-for="(value, year, index) in yearsAvr"
                :key="index"
                class="list-group-item"
              >
                {{ year }} - {{ value }}
              </li>
            </ul>
          </b-row>
        </b-col>
        <b-col>
          <b-row>
            <ul class="list-group w-100">
              <li class="list-group-item active">MySql data info</li>
              <li class="list-group-item">
                Last inserted ID: {{ lastInsertedId }}
              </li>
              <li class="list-group-item">Row counts: {{ rowCounts }}</li>
            </ul>
          </b-row>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  mounted() {
    this.getMysqlInfo();
  },
  data() {
    return {
      csvFile: null,
      lastInsertedId: null,
      rowCounts: null,
      saveToMysql: 0,
      showErr: false,
      showSuccess: false,
      contentSuccess: null,
      contentErr: null,
      totalHouseSold: null,
      avgPrice: null,
      numberOfCrimes: null,
      yearsAvr: []

    };
  },
  methods: {
    getMysqlInfo() {
      this.$http
        .get("/api/getinfo")
        .then((response) => {
          this.lastInsertedId = response.data.result.lastID;
          this.rowCounts = response.data.result.count;
        })
        .catch((error) => {
          console.log(response.data);
        });
    },
    submitFile() {
      const data = new FormData();
      data.append("csvfile", this.csvFile);
      data.append("saveToMysql", this.saveToMysql);
      this.$http
        .post("/api/proccess", data, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.data.error) {
            this.showErr = true;
            this.contentErr = response.data.error;
          }
          if (response.data.success) {
            this.getMysqlInfo();

            this.showSuccess = true;
            this.contentSuccess = response.data.success;

            this.totalHouseSold = response.data.result.houses_sold.sum;
            this.avgPrice = response.data.result.average_price.avr;
            
            this.numberOfCrimes = response.data.result.no_of_crimes_in_2011.sum;
            this.yearsAvr = response.data.result.avgByYearsInLondon;
         
         }
        })
        .catch((error) => {});
    },
  },
};
</script>
