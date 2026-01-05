<div class="container mt-4">

    <div class="text-center mb-4">
        <h3 class="fw-bold">GIS Based Applications Dashboard</h3>
    </div>

    <div class="card shadow-sm">
        <div class="card-header text-center fw-bold" style="background:#FBDDD1;">
            Summary of GIS Based Applications received on e-Service  
            <br>
            <small>NOC in respect of Block Forest</small>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Number of Applications</th>
                        <th>Approved Applications</th>
                        <th>Rejected Applications</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="font-size: 18px;">
                        <td class="bg-light fw-bold">
                            <?= $total ?>
                        </td>
                        <td class="bg-success text-white fw-bold">
                            <?= $totalBlockApproved ?>
                        </td>
                        <td class="bg-danger text-white fw-bold">
                            <?= $rejected ?>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

</div>
