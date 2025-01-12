-- Membuat database
CREATE DATABASE attendance;

-- Menggunakan database
USE attendance;

-- Tabel admin
CREATE TABLE admin (
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

-- Tabel dosen
CREATE TABLE dosen (
    nidn INT NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone_no VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (nidn)
);

-- Tabel fakultas
CREATE TABLE fakultas (
    id_fakultas INT NOT NULL AUTO_INCREMENT,
    nama_fakultas VARCHAR(255) NOT NULL UNIQUE,
    PRIMARY KEY (id_fakultas)
);

-- Tabel kelas
CREATE TABLE kelas (
    id_kelas INT NOT NULL AUTO_INCREMENT, 
    nama_kelas VARCHAR(255) NOT NULL UNIQUE,
    dosen_nidn INT NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_kelas),
    FOREIGN KEY (dosen_nidn) REFERENCES dosen(nidn) ON DELETE CASCADE
);

-- Tabel mahasiswa
CREATE TABLE mahasiswa (
    npm INT NOT NULL, 
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone_no VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    images VARCHAR(255),
    id_fakultas INT NOT NULL,
    id_kelas INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (npm),
    FOREIGN KEY (id_fakultas) REFERENCES fakultas(id_fakultas) ON DELETE CASCADE,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas) ON DELETE CASCADE
);

-- Tabel presensi
    CREATE TABLE presensi (
        id INT NOT NULL AUTO_INCREMENT,
        npm INT NOT NULL,
        id_fakultas INT NOT NULL,
        id_kelas INT NOT NULL,
        status VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    );  