-- Boru fit-up detay tablosu
CREATE TABLE pipe_fitup (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pipe_assembly_id INT NOT NULL,
    fitup_date DATE NOT NULL,
    fitter_id INT NOT NULL,
    fitup_status ENUM('prepared', 'aligned', 'tacked', 'completed') DEFAULT 'prepared',
    gap_check DECIMAL(4,2),
    alignment_check DECIMAL(4,2),
    root_face_check DECIMAL(4,2),
    notes TEXT,
    inspected_by INT,
    inspection_date DATE,
    FOREIGN KEY (pipe_assembly_id) REFERENCES pipe_assembly(id),
    FOREIGN KEY (fitter_id) REFERENCES personnel(id),
    FOREIGN KEY (inspected_by) REFERENCES personnel(id)
);

-- Test paketleri tablosu
CREATE TABLE test_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    package_name VARCHAR(100) NOT NULL,
    system_description TEXT,
    test_medium ENUM('water', 'air', 'steam', 'nitrogen', 'chemical') DEFAULT 'water',
    test_pressure DECIMAL(10,2),
    test_duration INT COMMENT 'in minutes',
    planned_test_date DATE,
    actual_test_date DATE,
    status ENUM('preparation', 'ready', 'testing', 'completed', 'approved') DEFAULT 'preparation',
    responsible_engineer_id INT,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (responsible_engineer_id) REFERENCES personnel(id)
);

-- Test sonuçları tablosu
CREATE TABLE test_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    test_package_id INT NOT NULL,
    test_type ENUM('hydrostatic', 'pneumatic', 'leak', 'functional') NOT NULL,
    test_date DATETIME NOT NULL,
    test_pressure_achieved DECIMAL(10,2),
    hold_time INT COMMENT 'in minutes',
    inspector_id INT NOT NULL,
    result ENUM('passed', 'failed', 'conditional') NOT NULL,
    remarks TEXT,
    attachments VARCHAR(255) COMMENT 'file paths',
    FOREIGN KEY (test_package_id) REFERENCES test_packages(id),
    FOREIGN KEY (inspector_id) REFERENCES personnel(id)
);

-- Teslimat (handover) belgeleri tablosu
CREATE TABLE handover_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    document_type ENUM('mechanical', 'electrical', 'instrumentation', 'civil', 'as_built') NOT NULL,
    document_number VARCHAR(50) NOT NULL,
    revision VARCHAR(10),
    title VARCHAR(255) NOT NULL,
    preparation_date DATE,
    review_date DATE,
    approval_date DATE,
    status ENUM('draft', 'under_review', 'approved', 'submitted', 'accepted') DEFAULT 'draft',
    responsible_person_id INT,
    client_representative_id INT,
    file_path VARCHAR(255),
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (responsible_person_id) REFERENCES personnel(id),
    FOREIGN KEY (client_representative_id) REFERENCES personnel(id)
);

-- Punch list (eksik listesi) tablosu
CREATE TABLE punch_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    item_number VARCHAR(20) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('mechanical', 'electrical', 'safety', 'civil', 'other') NOT NULL,
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    raised_by INT NOT NULL,
    raised_date DATE NOT NULL,
    assigned_to INT,
    target_completion_date DATE,
    actual_completion_date DATE,
    status ENUM('open', 'in_progress', 'completed', 'verified', 'closed') DEFAULT 'open',
    verification_date DATE,
    verified_by INT,
    remarks TEXT,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (raised_by) REFERENCES personnel(id),
    FOREIGN KEY (assigned_to) REFERENCES personnel(id),
    FOREIGN KEY (verified_by) REFERENCES personnel(id)
);