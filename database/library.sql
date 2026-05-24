CREATE DATABASE IF NOT EXISTS library_management_system;
USE library_management_system;

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin') NOT NULL DEFAULT 'admin',
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(150) NOT NULL,
    category VARCHAR(100) NOT NULL,
    isbn VARCHAR(50) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    available_quantity INT NOT NULL DEFAULT 0,
    added_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS issued_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    student_id INT NOT NULL,
    issue_date DATE NOT NULL,
    return_date DATE NOT NULL,
    status ENUM('issued', 'returned') NOT NULL DEFAULT 'issued',
    CONSTRAINT fk_issued_books_book FOREIGN KEY (book_id) REFERENCES books (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_issued_books_student FOREIGN KEY (student_id) REFERENCES students (id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admins (name, email, password, role, status)
SELECT 'Super Admin', 'admin@library.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'approved'
WHERE NOT EXISTS (SELECT 1 FROM admins WHERE email = 'admin@library.com');

INSERT INTO books (title, author, category, isbn, quantity, available_quantity)
SELECT 'To Kill a Mockingbird', 'Harper Lee', 'Classic Fiction', '9780061120084', 6, 6
WHERE NOT EXISTS (SELECT 1 FROM books WHERE isbn = '9780061120084');

INSERT INTO books (title, author, category, isbn, quantity, available_quantity)
SELECT '1984', 'George Orwell', 'Dystopian Fiction', '9780451524935', 8, 8
WHERE NOT EXISTS (SELECT 1 FROM books WHERE isbn = '9780451524935');

INSERT INTO books (title, author, category, isbn, quantity, available_quantity)
SELECT 'The Alchemist', 'Paulo Coelho', 'Inspirational Fiction', '9780062315007', 5, 5
WHERE NOT EXISTS (SELECT 1 FROM books WHERE isbn = '9780062315007');

INSERT INTO books (title, author, category, isbn, quantity, available_quantity)
SELECT 'Clean Code', 'Robert C. Martin', 'Programming', '9780132350884', 4, 4
WHERE NOT EXISTS (SELECT 1 FROM books WHERE isbn = '9780132350884');

INSERT INTO books (title, author, category, isbn, quantity, available_quantity)
SELECT 'The Hobbit', 'J.R.R. Tolkien', 'Fantasy', '9780547928227', 7, 7
WHERE NOT EXISTS (SELECT 1 FROM books WHERE isbn = '9780547928227');

INSERT INTO students (name, email, phone)
SELECT 'Aarav Sharma', 'aarav.sharma01@gmail.com', '9874563210'
WHERE NOT EXISTS (SELECT 1 FROM students WHERE email = 'aarav.sharma01@gmail.com');

INSERT INTO students (name, email, phone)
SELECT 'Diya Patel', 'diya.patel02@gmail.com', '9874563211'
WHERE NOT EXISTS (SELECT 1 FROM students WHERE email = 'diya.patel02@gmail.com');

INSERT INTO students (name, email, phone)
SELECT 'Rohan Verma', 'rohan.verma03@gmail.com', '9874563212'
WHERE NOT EXISTS (SELECT 1 FROM students WHERE email = 'rohan.verma03@gmail.com');

INSERT INTO students (name, email, phone)
SELECT 'Isha Khan', 'isha.khan04@gmail.com', '9874563213'
WHERE NOT EXISTS (SELECT 1 FROM students WHERE email = 'isha.khan04@gmail.com');

INSERT INTO students (name, email, phone)
SELECT 'Kabir Mehta', 'kabir.mehta05@gmail.com', '9874563214'
WHERE NOT EXISTS (SELECT 1 FROM students WHERE email = 'kabir.mehta05@gmail.com');

INSERT INTO books (title, author, category, isbn, quantity, available_quantity)
SELECT nb.title, nb.author, nb.category, nb.isbn, nb.quantity, nb.available_quantity
FROM (
    SELECT 'Engineering Mathematics I' AS title, 'Engineering Faculty Board' AS author, 'Engineering' AS category, '9781000000001' AS isbn, 5 AS quantity, 5 AS available_quantity
    UNION ALL SELECT 'Engineering Mathematics II', 'Engineering Faculty Board', 'Engineering', '9781000000002', 5, 5
    UNION ALL SELECT 'Engineering Mechanics', 'Engineering Faculty Board', 'Engineering', '9781000000003', 5, 5
    UNION ALL SELECT 'Basic Civil Engineering', 'Engineering Faculty Board', 'Engineering', '9781000000004', 5, 5
    UNION ALL SELECT 'Surveying and Geomatics', 'Engineering Faculty Board', 'Engineering', '9781000000005', 5, 5
    UNION ALL SELECT 'Strength of Materials', 'Engineering Faculty Board', 'Engineering', '9781000000006', 5, 5
    UNION ALL SELECT 'Structural Analysis', 'Engineering Faculty Board', 'Engineering', '9781000000007', 5, 5
    UNION ALL SELECT 'Fluid Mechanics', 'Engineering Faculty Board', 'Engineering', '9781000000008', 5, 5
    UNION ALL SELECT 'Hydraulics Engineering', 'Engineering Faculty Board', 'Engineering', '9781000000009', 5, 5
    UNION ALL SELECT 'Soil Mechanics', 'Engineering Faculty Board', 'Engineering', '9781000000010', 5, 5
    UNION ALL SELECT 'Transportation Engineering', 'Engineering Faculty Board', 'Engineering', '9781000000011', 5, 5
    UNION ALL SELECT 'Environmental Engineering', 'Engineering Faculty Board', 'Engineering', '9781000000012', 5, 5
    UNION ALL SELECT 'Thermodynamics', 'Engineering Faculty Board', 'Engineering', '9781000000013', 5, 5
    UNION ALL SELECT 'Heat Transfer', 'Engineering Faculty Board', 'Engineering', '9781000000014', 5, 5
    UNION ALL SELECT 'Theory of Machines', 'Engineering Faculty Board', 'Engineering', '9781000000015', 5, 5
    UNION ALL SELECT 'Machine Design', 'Engineering Faculty Board', 'Engineering', '9781000000016', 5, 5
    UNION ALL SELECT 'Manufacturing Processes', 'Engineering Faculty Board', 'Engineering', '9781000000017', 5, 5
    UNION ALL SELECT 'Industrial Engineering', 'Engineering Faculty Board', 'Engineering', '9781000000018', 5, 5
    UNION ALL SELECT 'Electrical Engineering Fundamentals', 'Engineering Faculty Board', 'Engineering', '9781000000019', 5, 5
    UNION ALL SELECT 'Electrical Machines', 'Engineering Faculty Board', 'Engineering', '9781000000020', 5, 5
    UNION ALL SELECT 'Power Systems', 'Engineering Faculty Board', 'Engineering', '9781000000021', 5, 5
    UNION ALL SELECT 'Digital Electronics', 'Engineering Faculty Board', 'Engineering', '9781000000022', 5, 5
    UNION ALL SELECT 'Analog Electronics', 'Engineering Faculty Board', 'Engineering', '9781000000023', 5, 5
    UNION ALL SELECT 'Microprocessors and Microcontrollers', 'Engineering Faculty Board', 'Engineering', '9781000000024', 5, 5
    UNION ALL SELECT 'Data Structures for Engineers', 'Engineering Faculty Board', 'Engineering', '9781000000025', 5, 5
    UNION ALL SELECT 'Computer Programming in C', 'Engineering Faculty Board', 'Engineering', '9781000000026', 5, 5
    UNION ALL SELECT 'Object-Oriented Programming', 'Engineering Faculty Board', 'Engineering', '9781000000027', 5, 5
    UNION ALL SELECT 'Database Management Systems', 'Engineering Faculty Board', 'Engineering', '9781000000028', 5, 5
    UNION ALL SELECT 'Control Systems', 'Engineering Faculty Board', 'Engineering', '9781000000029', 5, 5
    UNION ALL SELECT 'Renewable Energy Systems', 'Engineering Faculty Board', 'Engineering', '9781000000030', 5, 5
    UNION ALL SELECT 'Constitutional Law', 'Law Faculty Board', 'Law', '9781000000031', 5, 5
    UNION ALL SELECT 'Criminal Law', 'Law Faculty Board', 'Law', '9781000000032', 5, 5
    UNION ALL SELECT 'Contract Law', 'Law Faculty Board', 'Law', '9781000000033', 5, 5
    UNION ALL SELECT 'Law of Torts', 'Law Faculty Board', 'Law', '9781000000034', 5, 5
    UNION ALL SELECT 'Property Law', 'Law Faculty Board', 'Law', '9781000000035', 5, 5
    UNION ALL SELECT 'Administrative Law', 'Law Faculty Board', 'Law', '9781000000036', 5, 5
    UNION ALL SELECT 'Family Law', 'Law Faculty Board', 'Law', '9781000000037', 5, 5
    UNION ALL SELECT 'Labour Law', 'Law Faculty Board', 'Law', '9781000000038', 5, 5
    UNION ALL SELECT 'Company Law', 'Law Faculty Board', 'Law', '9781000000039', 5, 5
    UNION ALL SELECT 'Environmental Law', 'Law Faculty Board', 'Law', '9781000000040', 5, 5
    UNION ALL SELECT 'Cyber Law', 'Law Faculty Board', 'Law', '9781000000041', 5, 5
    UNION ALL SELECT 'Intellectual Property Rights', 'Law Faculty Board', 'Law', '9781000000042', 5, 5
    UNION ALL SELECT 'Jurisprudence', 'Law Faculty Board', 'Law', '9781000000043', 5, 5
    UNION ALL SELECT 'Legal Research Methodology', 'Law Faculty Board', 'Law', '9781000000044', 5, 5
    UNION ALL SELECT 'Evidence Law', 'Law Faculty Board', 'Law', '9781000000045', 5, 5
    UNION ALL SELECT 'Civil Procedure Code', 'Law Faculty Board', 'Law', '9781000000046', 5, 5
    UNION ALL SELECT 'Criminal Procedure Code', 'Law Faculty Board', 'Law', '9781000000047', 5, 5
    UNION ALL SELECT 'Alternative Dispute Resolution', 'Law Faculty Board', 'Law', '9781000000048', 5, 5
    UNION ALL SELECT 'International Law', 'Law Faculty Board', 'Law', '9781000000049', 5, 5
    UNION ALL SELECT 'Banking and Insurance Law', 'Law Faculty Board', 'Law', '9781000000050', 5, 5
    UNION ALL SELECT 'Taxation Law', 'Law Faculty Board', 'Law', '9781000000051', 5, 5
    UNION ALL SELECT 'Consumer Protection Law', 'Law Faculty Board', 'Law', '9781000000052', 5, 5
    UNION ALL SELECT 'Human Rights Law', 'Law Faculty Board', 'Law', '9781000000053', 5, 5
    UNION ALL SELECT 'Competition Law', 'Law Faculty Board', 'Law', '9781000000054', 5, 5
    UNION ALL SELECT 'Medical Jurisprudence', 'Law Faculty Board', 'Law', '9781000000055', 5, 5
    UNION ALL SELECT 'Drafting Pleading and Conveyancing', 'Law Faculty Board', 'Law', '9781000000056', 5, 5
    UNION ALL SELECT 'Moot Court Practice', 'Law Faculty Board', 'Law', '9781000000057', 5, 5
    UNION ALL SELECT 'Media Law', 'Law Faculty Board', 'Law', '9781000000058', 5, 5
    UNION ALL SELECT 'Women and Child Law', 'Law Faculty Board', 'Law', '9781000000059', 5, 5
    UNION ALL SELECT 'Forensic Law', 'Law Faculty Board', 'Law', '9781000000060', 5, 5
    UNION ALL SELECT 'Pharmaceutical Chemistry', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000061', 5, 5
    UNION ALL SELECT 'Organic Chemistry for Pharmacy', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000062', 5, 5
    UNION ALL SELECT 'Inorganic Pharmaceutical Chemistry', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000063', 5, 5
    UNION ALL SELECT 'Physical Pharmaceutics', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000064', 5, 5
    UNION ALL SELECT 'Pharmaceutics I', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000065', 5, 5
    UNION ALL SELECT 'Pharmaceutics II', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000066', 5, 5
    UNION ALL SELECT 'Pharmacognosy', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000067', 5, 5
    UNION ALL SELECT 'Pharmacology I', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000068', 5, 5
    UNION ALL SELECT 'Pharmacology II', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000069', 5, 5
    UNION ALL SELECT 'Biopharmaceutics and Pharmacokinetics', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000070', 5, 5
    UNION ALL SELECT 'Pharmaceutical Analysis', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000071', 5, 5
    UNION ALL SELECT 'Clinical Pharmacy', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000072', 5, 5
    UNION ALL SELECT 'Hospital Pharmacy', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000073', 5, 5
    UNION ALL SELECT 'Pharmaceutical Microbiology', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000074', 5, 5
    UNION ALL SELECT 'Drug Regulatory Affairs', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000075', 5, 5
    UNION ALL SELECT 'Novel Drug Delivery Systems', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000076', 5, 5
    UNION ALL SELECT 'Medicinal Chemistry I', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000077', 5, 5
    UNION ALL SELECT 'Medicinal Chemistry II', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000078', 5, 5
    UNION ALL SELECT 'Community Pharmacy', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000079', 5, 5
    UNION ALL SELECT 'Pathophysiology', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000080', 5, 5
    UNION ALL SELECT 'Human Anatomy and Physiology', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000081', 5, 5
    UNION ALL SELECT 'Pharmaceutical Biotechnology', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000082', 5, 5
    UNION ALL SELECT 'Quality Assurance in Pharmaceuticals', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000083', 5, 5
    UNION ALL SELECT 'Industrial Pharmacy', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000084', 5, 5
    UNION ALL SELECT 'Herbal Drug Technology', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000085', 5, 5
    UNION ALL SELECT 'Toxicology', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000086', 5, 5
    UNION ALL SELECT 'Pharmaceutical Marketing', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000087', 5, 5
    UNION ALL SELECT 'Biochemistry for Pharmacy', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000088', 5, 5
    UNION ALL SELECT 'Computer Applications in Pharmacy', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000089', 5, 5
    UNION ALL SELECT 'Research Methodology in Pharmacy', 'Pharmacy Faculty Board', 'Pharmacy', '9781000000090', 5, 5
    UNION ALL SELECT 'Principles of Management', 'Management Faculty Board', 'Management', '9781000000091', 5, 5
    UNION ALL SELECT 'Organizational Behaviour', 'Management Faculty Board', 'Management', '9781000000092', 5, 5
    UNION ALL SELECT 'Business Communication', 'Management Faculty Board', 'Management', '9781000000093', 5, 5
    UNION ALL SELECT 'Financial Management', 'Management Faculty Board', 'Management', '9781000000094', 5, 5
    UNION ALL SELECT 'Marketing Management', 'Management Faculty Board', 'Management', '9781000000095', 5, 5
    UNION ALL SELECT 'Human Resource Management', 'Management Faculty Board', 'Management', '9781000000096', 5, 5
    UNION ALL SELECT 'Operations Management', 'Management Faculty Board', 'Management', '9781000000097', 5, 5
    UNION ALL SELECT 'Strategic Management', 'Management Faculty Board', 'Management', '9781000000098', 5, 5
    UNION ALL SELECT 'Business Statistics', 'Management Faculty Board', 'Management', '9781000000099', 5, 5
    UNION ALL SELECT 'Managerial Economics', 'Management Faculty Board', 'Management', '9781000000100', 5, 5
    UNION ALL SELECT 'Entrepreneurship Development', 'Management Faculty Board', 'Management', '9781000000101', 5, 5
    UNION ALL SELECT 'Retail Management', 'Management Faculty Board', 'Management', '9781000000102', 5, 5
    UNION ALL SELECT 'Supply Chain Management', 'Management Faculty Board', 'Management', '9781000000103', 5, 5
    UNION ALL SELECT 'Project Management', 'Management Faculty Board', 'Management', '9781000000104', 5, 5
    UNION ALL SELECT 'Business Analytics', 'Management Faculty Board', 'Management', '9781000000105', 5, 5
    UNION ALL SELECT 'Corporate Governance', 'Management Faculty Board', 'Management', '9781000000106', 5, 5
    UNION ALL SELECT 'International Business', 'Management Faculty Board', 'Management', '9781000000107', 5, 5
    UNION ALL SELECT 'Service Management', 'Management Faculty Board', 'Management', '9781000000108', 5, 5
    UNION ALL SELECT 'Leadership and Team Building', 'Management Faculty Board', 'Management', '9781000000109', 5, 5
    UNION ALL SELECT 'Consumer Behaviour', 'Management Faculty Board', 'Management', '9781000000110', 5, 5
    UNION ALL SELECT 'Digital Marketing', 'Management Faculty Board', 'Management', '9781000000111', 5, 5
    UNION ALL SELECT 'Risk Management', 'Management Faculty Board', 'Management', '9781000000112', 5, 5
    UNION ALL SELECT 'Financial Accounting', 'Management Faculty Board', 'Management', '9781000000113', 5, 5
    UNION ALL SELECT 'Cost Accounting', 'Management Faculty Board', 'Management', '9781000000114', 5, 5
    UNION ALL SELECT 'Business Law for Managers', 'Management Faculty Board', 'Management', '9781000000115', 5, 5
    UNION ALL SELECT 'Negotiation and Conflict Management', 'Management Faculty Board', 'Management', '9781000000116', 5, 5
    UNION ALL SELECT 'Event Management', 'Management Faculty Board', 'Management', '9781000000117', 5, 5
    UNION ALL SELECT 'Hospitality Management', 'Management Faculty Board', 'Management', '9781000000118', 5, 5
    UNION ALL SELECT 'Office Administration', 'Management Faculty Board', 'Management', '9781000000119', 5, 5
    UNION ALL SELECT 'Business Research Methods', 'Management Faculty Board', 'Management', '9781000000120', 5, 5
) AS nb
LEFT JOIN books b ON b.isbn = nb.isbn
WHERE b.isbn IS NULL;

INSERT INTO students (name, email, phone)
SELECT ns.name, ns.email, ns.phone
FROM (
    SELECT 'Aarav Sharma' AS name, 'aarav.sharma01@gmail.com' AS email, '9874563101' AS phone
    UNION ALL SELECT 'Diya Patel', 'diya.patel02@gmail.com', '9874563102'
    UNION ALL SELECT 'Rohan Verma', 'rohan.verma03@gmail.com', '9874563103'
    UNION ALL SELECT 'Isha Khan', 'isha.khan04@gmail.com', '9874563104'
    UNION ALL SELECT 'Kabir Mehta', 'kabir.mehta05@gmail.com', '9874563105'
    UNION ALL SELECT 'Meera Joshi', 'meera.joshi06@gmail.com', '9874563106'
    UNION ALL SELECT 'Arjun Nair', 'arjun.nair07@gmail.com', '9874563107'
    UNION ALL SELECT 'Sana Ahmed', 'sana.ahmed08@gmail.com', '9874563108'
    UNION ALL SELECT 'Vivek Singh', 'vivek.singh09@gmail.com', '9874563109'
    UNION ALL SELECT 'Nisha Rao', 'nisha.rao10@gmail.com', '9874563110'
    UNION ALL SELECT 'Rahul Gupta', 'rahul.gupta11@gmail.com', '9874563111'
    UNION ALL SELECT 'Pooja Sharma', 'pooja.sharma12@gmail.com', '9874563112'
    UNION ALL SELECT 'Karan Malhotra', 'karan.malhotra13@gmail.com', '9874563113'
    UNION ALL SELECT 'Ananya Iyer', 'ananya.iyer14@gmail.com', '9874563114'
    UNION ALL SELECT 'Siddharth Jain', 'siddharth.jain15@gmail.com', '9874563115'
    UNION ALL SELECT 'Neha Kapoor', 'neha.kapoor16@gmail.com', '9874563116'
    UNION ALL SELECT 'Aman Das', 'aman.das17@gmail.com', '9874563117'
    UNION ALL SELECT 'Priya Nair', 'priya.nair18@gmail.com', '9874563118'
    UNION ALL SELECT 'Tanya Verma', 'tanya.verma19@gmail.com', '9874563119'
    UNION ALL SELECT 'Mohit Bansal', 'mohit.bansal20@gmail.com', '9874563120'
    UNION ALL SELECT 'Ramesh Kumar', 'ramesh.kumar21@gmail.com', '9874563121'
    UNION ALL SELECT 'Ritu Sinha', 'ritu.sinha22@gmail.com', '9874563122'
    UNION ALL SELECT 'Deepak Rao', 'deepak.rao23@gmail.com', '9874563123'
    UNION ALL SELECT 'Suman Kaur', 'suman.kaur24@gmail.com', '9874563124'
    UNION ALL SELECT 'Kartik Roy', 'kartik.roy25@gmail.com', '9874563125'
) AS ns
LEFT JOIN students s ON s.email = ns.email
WHERE s.email IS NULL;
