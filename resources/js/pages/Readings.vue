<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Blood Sugar Readings</div>

                    <vue-good-table
                        :columns="columns"
                        :rows="rows"
                        :pagination-options="{
                            enabled: true,
                            perPage: 5,
                            rowsPerPageLabel: 'Per Page',
                            nextLabel: 'NEXT',
                            prevLabel: 'PREV',
                            perPageDropdownEnabled: false,
                        }"
                        :compactMode="true"
                        :sort-options="{ enabled: false }"
                        theme="nocturnal"
                        styleClass="vgt-table striped"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            columns: [
                {
                    label: "Date",
                    field: "read_at",
                    type: "date",
                    dateInputFormat: "yyyy-MM-dd HH:mm:ss", // expects 2018-03-16
                    dateOutputFormat: "eee, d-MMM-yyyy h:mm:ss a", // outputs Mar 16th 2018
                    tdClass: "text-start",
                    thClass: "text-start",
                },
                {
                    label: "Type",
                    field: "type",
                },
                {
                    label: "Reading",
                    field: "reading",
                    type: "number",
                },
            ],
            rows: [],
        };
    },
    mounted() {
        axios.get("/api/v1/readings").then((response) => {
            this.rows = response.data.data;
        });
    },
};
</script>
