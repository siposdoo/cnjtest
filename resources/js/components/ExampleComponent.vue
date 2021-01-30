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
      this.getMysqlInfo()
  },
  data() {
    return {
      csvFile: null,
      lastInsertedId: null,
      rowCounts: null,
      saveToMysql: 0,
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
          }
          if (response.data.success) {
              this.getMysqlInfo()
          }
        })
        .catch((error) => {});
    },
  },
};
</script>
