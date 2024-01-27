<?php

namespace App\Helpers;

class EncryptOrthogonal
{
    function orthogonal_encrypt($plain_text, $step_size)
    {
        $plain_text = str_replace(" ", "-", $plain_text);
        $matrix_representation = [];
        $encrypted_text = "";

        // Mendapatkan panjang teks asli
        $text_length = strlen($plain_text);

        // Membuat matriks dengan karakter-karakter dari teks asli
        for ($i = 0; $i < $step_size; $i++) {
            $matrix_row = [];
            for ($j = 0; $j < ceil($text_length / $step_size); $j++) {
                $index = $j * $step_size + $i;
                if ($index < $text_length) {
                    $matrix_row[] = $plain_text[$index];
                } else {
                    $matrix_row[] = '@';
                }
            }
            $matrix_representation[] = $matrix_row;
        }

        // Enkripsi teks dari matriks
        $matrix_height = $step_size;
        $matrix_width = ceil($text_length / $matrix_height);

        for ($i = 0; $i < $matrix_height; $i++) {
            if ($i % 2 == 0) {
                for ($j = $matrix_width - 1; $j >= 0; $j--) {
                    if ($matrix_representation[$i][$j] != '@') {
                        $encrypted_text .= $matrix_representation[$i][$j];
                    } else {
                        $encrypted_text .= '@';
                    }
                }
            } else {
                for ($j = 0; $j < $matrix_width; $j++) {
                    if ($matrix_representation[$i][$j] != '@') {
                        $encrypted_text .= $matrix_representation[$i][$j];
                    } else {
                        $encrypted_text .= '@';
                    }
                }
            }
        }

        return $encrypted_text;
    }


    function orthogonal_decrypt($encrypted_text, $step_size)
    {
        $matrix_representation = [];
        $decrypted_text = "";

        // Mendapatkan panjang teks terenkripsi
        $text_length = strlen($encrypted_text);

        // Menghitung lebar dan tinggi matriks
        $matrix_height = $step_size;
        $matrix_width = ceil($text_length / $matrix_height);

        // Inisialisasi matriks kosong
        for ($i = 0; $i < $matrix_height; $i++) {
            $matrix_representation[] = array_fill(0, $matrix_width, 0);
        }

        // Mengisi matriks dengan teks terenkripsi
        $index = 0;
        for ($i = 0; $i < $matrix_height; $i++) {
            if ($i % 2 == 0) {
                for ($j = $matrix_width - 1; $j >= 0; $j--) {
                    $matrix_representation[$i][$j] = $encrypted_text[$index];
                    $index++;
                }
            } else {
                for ($j = 0; $j < $matrix_width; $j++) {
                    $matrix_representation[$i][$j] = $encrypted_text[$index];
                    $index++;
                }
            }
        }

        // Membaca karakter dari matriks sesuai dengan urutan yang benar untuk mendapatkan teks asli
        for ($j = 0; $j < $matrix_width; $j++) {
            for ($i = 0; $i < $matrix_height; $i++) {
                if ($matrix_representation[$i][$j] != '@') {
                    $decrypted_text .= $matrix_representation[$i][$j];
                }
            }
        }

        return str_replace("-", " ", $decrypted_text); // Mengganti karakter '-' dengan spasi
    }


    // $plain_text = "Contoh teks yang akan dienkripsi";
    // $step_size = 4;

    // $cipher = orthogonal_encrypt(str_replace(" ", "-", $plain_text), $step_size);
    // echo "Ciphertext: " . $cipher; // Menampilkan teks terenkripsi

    // echo "\n";
    // Contoh penggunaan dekripsi
    // $encrypted_text = $cipher;
    // $step_size = 4;

    // $deciphered_text = orthogonal_decrypt($encrypted_text, $step_size);
    // echo "Plaintext: " . $deciphered_text; // Menampilkan teks terdekrips
}
