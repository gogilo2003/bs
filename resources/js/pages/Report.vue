<template>
    <div class="container-fluid">
        <div class="btn-toolbar" role="toolbar" aria-label="">
            <div class="btn-group" role="group" aria-label="">
                <button type="button" class="btn btn-outline-dark">
                    TODAY
                </button>
                <button type="button" class="btn btn-outline-dark">
                    THIS WEEK
                </button>
                <button type="button" class="btn btn-outline-dark">
                    THIS MONTH
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-header"></div>
            <table class="table table-striped">
                <thead>
                    <th>
                        <td>DATE</td>
                        <td>TIME</td>
                        <td>TYPE</td>
                        <td>BS</td>
                    </th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    mounted() {},
};
</script>
