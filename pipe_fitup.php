<?php
include('../includes/header.php');
include('../config.php');

// Boru fit-up kayıtlarını getirme
$query = "SELECT pf.*, pa.line_no, pa.spec, fitter.full_name as fitter_name, 
                 inspector.full_name as inspector_name
          FROM pipe_fitup pf
          JOIN pipe_assembly pa ON pf.pipe_assembly_id = pa.id
          JOIN personnel fitter ON pf.fitter_id = fitter.id
          LEFT JOIN personnel inspector ON pf.inspected_by = inspector.id
          ORDER BY pf.fitup_date DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container-fluid">
    <h2 class="mb-4">Boru Fit-Up Takip Modülü</h2>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Fit-Up Kayıtları</h6>
            <a href="add_fitup.php" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Yeni Fit-Up Kaydı
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="fitupTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Hat No</th>
                            <th>Spec</th>
                            <th>Fit-Up Tarihi</th>
                            <th>Fitter</th>
                            <th>Durum</th>
                            <th>Kontrol</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['line_no'] ?></td>
                            <td><?= $row['spec'] ?></td>
                            <td><?= date('d.m.Y', strtotime($row['fitup_date'])) ?></td>
                            <td><?= $row['fitter_name'] ?></td>
                            <td>
                                <span class="badge badge-<?= 
                                    $row['fitup_status'] == 'completed' ? 'success' : 
                                    ($row['fitup_status'] == 'tacked' ? 'info' : 'warning') 
                                ?>">
                                    <?= strtoupper(str_replace('_', ' ', $row['fitup_status'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if($row['inspector_name']): ?>
                                    <span class="badge badge-success">Kontrol Edildi</span><br>
                                    <small><?= $row['inspector_name'] ?></small>
                                <?php else: ?>
                                    <span class="badge badge-warning">Bekliyor</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="view_fitup.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm" title="Detay">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="inspect_fitup.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm" title="Kontrol">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>