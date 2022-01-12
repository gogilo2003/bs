<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="btn-toolbar" role="toolbar" aria-label="">
                            <button type="button" class="btn btn-sm btn-outline-dark" @click="today">
                                TODAY
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-dark" @click="thisWeek">
                                THIS WEEK
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-dark" @click="thisMonth">
                                THIS MONTH
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-dark" @click="reset">
                                RESET
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-dark" @click="print">
                                PRINT
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card" ref="report">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th>
                                <th>DATE</th>
                                <th>TIME</th>
                                <th>TYPE</th>
                                <th>BS</th>
                            </th>
                        </thead>
                        <tbody>
                            <tr v-for="(reading,i) in filter" :key="reading.id">
                                <td v-text="i+1"></td>
                                <td v-text="formatDate(reading.read_at)"></td>
                                <td v-text="formatTime(reading.read_at)"></td>
                                <td v-text="capitalize(reading.type)"></td>
                                <td v-text="reading.reading"></td>
                            </tr>
                        </tbody>
                    </table>
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

            // fetch({
            //     url: `/api/v1/readings/download/${type}`, //your url
            //     method: "GET",
            //     responseType: "blob", // important
            // }).then((response) => {
            //     const url = window.URL.createObjectURL(
            //         new Blob([response.data])
            //     );
            //     // const link = document.createElement("a");
            //     // link.href = url;
            //     // link.setAttribute("download", "file.pdf"); //or any other extension
            //     // document.body.appendChild(link);
            //     // link.click();
            //     var link = document.createElement("a");
            //     link.href = url;
            //     link.download = "readings.pdf";
            //     link.dispatchEvent(new MouseEvent("click"));
            // });

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
                    a.download = "filename.pdf";
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
            const days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
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
