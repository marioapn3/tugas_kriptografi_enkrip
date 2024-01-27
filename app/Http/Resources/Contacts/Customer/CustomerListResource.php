<?php

namespace App\Http\Resources\Contacts\Customer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerListResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
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
    public function toArray($request)
    {
        return [
            'data' => $this->transformCollection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Success get customer list",
                'pagination' => $this->metaData()
            ]
        ];
    }

    private function transformData($data)
    {
        return [
            'id' =>  $data->id,
            'name' => $this->orthogonal_decrypt($data->name, 4),
            'description' => $this->orthogonal_decrypt($data->description, 4),
            'email' => $this->orthogonal_decrypt($data->email, 4),
            'phone_number' => $this->orthogonal_decrypt($data->phone_number, 4),
            'address' => $this->orthogonal_decrypt($data->address, 4),
            'type' => $this->orthogonal_decrypt($data->type, 4),
            'city' => $this->orthogonal_decrypt($data->city, 4),
            'portal' => $this->orthogonal_decrypt($data->portal, 4),
            'default' => (bool) $data->default
        ];
    }

    private function transformCollection($collection)
    {
        return $collection->transform(function ($data) {
            return $this->transformData($data);
        });
    }

    private function metaData()
    {
        return [
            "total" => $this->total(),
            "count" => $this->count(),
            "per_page" => (int)$this->perPage(),
            "current_page" => $this->currentPage(),
            "total_pages" => $this->lastPage(),
            "links" => [
                "next" => $this->nextPageUrl()
            ],
        ];
    }
}
