-- Çelik montaj tablosu
CREATE TABLE steel_assembly (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    drawing_no VARCHAR(50),
    element_code VARCHAR(50),
    assembly_date DATE,
    status ENUM('planned', 'in_progress', 'completed', 'approved', 'rejected') DEFAULT 'planned',
    responsible_person_id INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (responsible_person_id) REFERENCES personnel(id)
);

-- Boru montaj tablosu
CREATE TABLE pipe_assembly (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    line_no VARCHAR(50),
    spec VARCHAR(50),
    diameter VARCHAR(20),
    length DECIMAL(10,2),
    status ENUM('planned', 'cutting', 'fitting', 'welding', 'completed', 'approved') DEFAULT 'planned',
    welder_id INT,
    inspector_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (welder_id) REFERENCES personnel(id),
    FOREIGN KEY (inspector_id) REFERENCES personnel(id)
);

-- Kaynak işlemleri tablosu
CREATE TABLE welding_operations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pipe_assembly_id INT,
    weld_no VARCHAR(50),
    welder_id INT,
    welding_date DATE,
    welding_method ENUM('SMAW', 'GTAW', 'GMAW', 'FCAW', 'SAW'),
    rod_batch_no VARCHAR(50),
    status ENUM('planned', 'completed', 'inspected', 'approved', 'rejected') DEFAULT 'planned',
    inspection_date DATE,
    inspection_result TEXT,
    FOREIGN KEY (pipe_assembly_id) REFERENCES pipe_assembly(id),
    FOREIGN KEY (welder_id) REFERENCES personnel(id)
);

-- Kalite kontrol tablosu
CREATE TABLE quality_control (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    control_type ENUM('visual', 'dimensional', 'welding', 'ndt', 'pressure_test', 'final_inspection'),
    control_date DATE,
    responsible_id INT,
    reference_document VARCHAR(100),
    status ENUM('planned', 'completed', 'approved', 'non_conformity') DEFAULT 'planned',
    results TEXT,
    corrective_actions TEXT,
    next_control_date DATE,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (responsible_id) REFERENCES personnel(id)
);

-- NDT (Tahribatsız Muayene) kayıtları
CREATE TABLE ndt_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qc_id INT,
    ndt_method ENUM('RT', 'UT', 'PT', 'MT', 'VT'),
    performed_by INT,
    performed_date DATE,
    result ENUM('acceptable', 'repair_required', 'reject'),
    report_no VARCHAR(50),
    FOREIGN KEY (qc_id) REFERENCES quality_control(id),
    FOREIGN KEY (performed_by) REFERENCES personnel(id)
);