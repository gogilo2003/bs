<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-2">
                    <div class="card-header">
                        <div class="btn-toolbar" role="toolbar" aria-label="">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-dark me-1"
                                @click="today"
                            >
                                TODAY
                            </button>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-dark me-1"
                                @click="thisWeek"
                            >
                                WEEK
                            </button>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-dark me-1"
                                @click="thisMonth"
                            >
                                MONTH
                            </button>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-dark me-1"
                                @click="reset"
                            >
                                RESET
                            </button>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-dark"
                                @click="print"
                            >
                                PRINT
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card" ref="report">
                    <vue-good-table
                        :columns="columns"
                        :rows="filter"
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
import { subWeeks, subMonths, isAfter } from "date-fns/fp";
export default {
    data: () => {
        return {
            columns: [
                {
                    label: "DATE",
                    field: "read_at",
                    type: "date",
                    dateInputFormat: "yyyy-MM-dd HH:mm:ss", // expects 2018-03-16
                    dateOutputFormat: "eee, d-MMM-yyyy", // outputs Mar 16th 2018
                    tdClass: "text-start",
                    thClass: "text-start",
                },
                {
                    label: "TIME",
                    field: "read_at",
                    type: "date",
                    dateInputFormat: "yyyy-MM-dd HH:mm:ss", // expects 2018-03-16
                    dateOutputFormat: "h:mm:ss a", // outputs Mar 16th 2018
                    tdClass: "text-start",
                    thClass: "text-start",
                },
                {
                    label: "TYPE",
                    field: "type",
                },
                {
                    label: "READING",
                    field: "reading",
                    type: "number",
                },
            ],
            readings: [],
            filter: [],
            report_type: "all",
        };
    },
    mounted() {
        axios.get("/api/v1/readings").then((response) => {
            this.readings = response.data.data;
            this.reset();
        });
    },
    methods: {
        print() {
            let type = this.report_type;
            fetch(`/api/v1/readings/download/${type}`, {
                method: "GET",
                headers: new Headers({
                    Authorization: "Bearer " + localStorage.getItem("token"),
                }),
            })
                .then((response) => response.blob())
                .then((blob) => {
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = url;
                    a.download = "$type.pdf";
                    document.body.appendChild(a); // we need to append the element to the dom -> otherwise it will not work in firefox
                    a.click();
                    a.remove(); //afterwards we remove the element again
                });
        },
        reset() {
            this.filter = this.readings;
            this.report_type = "all";
        },
        today() {
            this.filter = this.readings.filter(
                (item) =>
                    new Date(item.read_at).toDateString() ===
                    new Date().toDateString()
            );
            this.report_type = "today";
        },
        thisWeek() {
            let end = new Date();
            let start = subWeeks(1, end);
            start.setHours(0);
            start.setMinutes(0);
            start.setSeconds(0);
            this.filter = this.readings.filter((item) =>
                isAfter(start, new Date(item.read_at))
            );
            this.report_type = "week";
        },
        thisMonth() {
            let end = new Date();
            let start = subMonths(1, end);
            start.setHours(0);
            start.setMinutes(0);
            start.setSeconds(0);
            this.filter = this.readings.filter((item) =>
                isAfter(start, new Date(item.read_at))
            );
            this.report_type = "month";
        },
        formatDate: (value) => {
            const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            const months = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];
            let date = new Date(value);
            return `${days[date.getDay()]} ${date.getDate()}-${
                months[date.getMonth()]
            }-${date.getFullYear()}`;
        },
        formatTime: (value) => {
            let time = new Date(value);
            var H = time.getHours();
            var h = H % 12 || 12;
            var ampm = H < 12 || H === 24 ? "AM" : "PM";

            return `${h}:${time
                .getMinutes()
                .toString()
                .padStart(2, "0")} ${ampm}`;
        },
        capitalize: (value) => {
            if (!value) return "";
            value = value.toString();
            return value.toUpperCase();
        },
    },
};
</script>
