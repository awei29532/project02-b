<?php

use App\Models\Permission;
use App\Models\PermissionLevel;

class PermissionSeederOld extends BasePermissionSeeder
{

    const level = [
        [
            'id' => 1,
            'type' => 1,
            'children' => [
                [
                    'id' => 2,
                    'type' => 1,
                    'children' => [
                        [
                            'id' => 3,
                            'type' => 2
                        ]
                    ]
                ]
            ],
        ],
        [
            'id' => 4,
            'type' => 4,
            'children' => [
                [
                    'id' => 5,
                    'type' => 4,
                    'children' => [
                        [
                            'id' => 6,
                            'type' => 5,
                        ],
                        [
                            'id' => 7,
                            'type' => 5,
                        ],
                        [
                            'id' => 8,
                            'type' => 5,
                        ],
                        [
                            'id' => 9,
                            'type' => 5,
                        ],
                        [
                            'id' => 10,
                            'type' => 5,
                        ],
                        [
                            'id' => 11,
                            'type' => 5,
                        ],
                        [
                            'id' => 12,
                            'type' => 5,
                        ],
                        [
                            'id' => 13,
                            'type' => 5,
                        ],
                        [
                            'id' => 14,
                            'type' => 5,
                        ],
                        [
                            'id' => 15,
                            'type' => 5,
                        ]
                    ]
                ],
                [
                    'id' => 16,
                    'type' => 4,
                    'children' => [
                        [
                            'id' => 17,
                            'type' => 16,
                        ],
                        [
                            'id' => 18,
                            'type' => 16,
                        ],
                        [
                            'id' => 19,
                            'type' => 16,
                        ],
                        [
                            'id' => 20,
                            'type' => 16,
                        ]
                    ]
                ],
                [
                    'id' => 21,
                    'type' => 4,
                    'children' => [
                        [
                            'id' => 22,
                            'type' => 21,
                        ],
                        [
                            'id' => 23,
                            'type' => 21,
                        ],
                        [
                            'id' => 24,
                            'type' => 21,
                        ],
                        [
                            'id' => 25,
                            'type' => 21,
                        ],
                        [
                            'id' => 26,
                            'type' => 21,
                        ]
                    ]
                ],
                [
                    'id' => 27,
                    'type' => 4,
                    'children' => [
                        [
                            'id' => 28,
                            'type' => 27,
                        ],
                        [
                            'id' => 29,
                            'type' => 27,
                        ],
                        [
                            'id' => 30,
                            'type' => 27,
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 31,
            'type' => 31,
            'children' => [
                [
                    'id' => 32,
                    'type' => 31,
                    'children' => [
                        [
                            'id' => 33,
                            'type' => 32,
                        ],
                        [
                            'id' => 34,
                            'type' => 32,
                        ],
                        [
                            'id' => 35,
                            'type' => 32,
                        ],
                        [
                            'id' => 36,
                            'type' => 32,
                        ],
                        [
                            'id' => 37,
                            'type' => 32,
                        ],
                        [
                            'id' => 38,
                            'type' => 32,
                        ],
                        [
                            'id' => 39,
                            'type' => 32,
                        ],
                        [
                            'id' => 40,
                            'type' => 32,
                        ],
                        [
                            'id' => 41,
                            'type' => 32,
                        ],
                        [
                            'id' => 42,
                            'type' => 32,
                        ],
                        [
                            'id' => 43,
                            'type' => 32,
                        ]
                    ]
                ],
                [
                    'id' => 44,
                    'type' => 31,
                    'children' => [
                        [
                            'id' => 45,
                            'type' => 44,
                        ],
                        [
                            'id' => 46,
                            'type' => 44,
                        ],
                        [
                            'id' => 47,
                            'type' => 44,
                        ],
                        [
                            'id' => 48,
                            'type' => 44,
                        ],
                        [
                            'id' => 49,
                            'type' => 44,
                        ],
                        [
                            'id' => 50,
                            'type' => 44,
                        ],
                        [
                            'id' => 51,
                            'type' => 44,
                        ],
                        [
                            'id' => 52,
                            'type' => 44,
                        ],
                        [
                            'id' => 53,
                            'type' => 44,
                        ],
                        [
                            'id' => 54,
                            'type' => 44,
                        ],
                        [
                            'id' => 55,
                            'type' => 44,
                        ],
                        [
                            'id' => 56,
                            'type' => 44,
                        ]
                    ]
                ],
                [
                    'id' => 57,
                    'type' => 31,
                    'children' => [
                        [
                            'id' => 58,
                            'type' => 57,
                        ],
                        [
                            'id' => 59,
                            'type' => 57,
                        ]
                    ]
                ],
                [
                    'id' => 60,
                    'type' => 31,
                    'children' => [
                        [
                            'id' => 61,
                            'type' => 60,
                        ],
                        [
                            'id' => 62,
                            'type' => 60,
                        ],
                        [
                            'id' => 63,
                            'type' => 60,
                        ],
                        [
                            'id' => 64,
                            'type' => 60,
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 65,
            'type' => 65,
            'children' => [
                [
                    'id' => 66,
                    'type' => 65,
                    'children' => [
                        [
                            'id' => 67,
                            'type' => 66,
                        ],
                        [
                            'id' => 68,
                            'type' => 66,
                        ],
                        [
                            'id' => 69,
                            'type' => 66,
                        ],
                        [
                            'id' => 70,
                            'type' => 66,
                        ],
                        [
                            'id' => 71,
                            'type' => 66,
                        ],
                        [
                            'id' => 72,
                            'type' => 66,
                        ],
                        [
                            'id' => 73,
                            'type' => 66,
                        ],
                        [
                            'id' => 74,
                            'type' => 66,
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 75,
            'type' => 75,
            'children' => [
                [
                    'id' => 76,
                    'type' => 75,
                    'children' => [
                        [
                            'id' => 77,
                            'type' => 76,
                        ]
                    ]
                ],
                [
                    'id' => 78,
                    'type' => 75,
                    'children' => [
                        [
                            'id' => 79,
                            'type' => 78,
                        ]
                    ]
                ],
                [
                    'id' => 80,
                    'type' => 75,
                    'children' => [
                        [
                            'id' => 81,
                            'type' => 80,
                        ]
                    ]
                ],
                [
                    'id' => 82,
                    'type' => 75,
                    'children' => [
                        [
                            'id' => 83,
                            'type' => 82,
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 84,
            'type' => 84,
            'children' => [
                [
                    'id' => 85,
                    'type' => 84,
                    'children' => [
                        [
                            'id' => 86,
                            'type' => 85,
                        ]
                    ]
                ],
                [
                    'id' => 87,
                    'type' => 84,
                    'children' => [
                        [
                            'id' => 88,
                            'type' => 87,
                        ]
                    ]
                ],
                [
                    'id' => 89,
                    'type' => 84,
                    'children' => [
                        [
                            'id' => 90,
                            'type' => 89,
                        ]
                    ]
                ],
                [
                    'id' => 91,
                    'type' => 84,
                    'children' => [
                        [
                            'id' => 92,
                            'type' => 91,
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 93,
            'type' => 93,
            'children' => [
                [
                    'id' => 94,
                    'type' => 93,
                    'children' => [
                        [
                            'id' => 95,
                            'type' => 94,
                        ]
                    ]
                ],
                [
                    'id' => 96,
                    'type' => 93,
                    'children' => [
                        [
                            'id' => 97,
                            'type' => 96,
                        ]
                    ]
                ],
                [
                    'id' => 98,
                    'type' => 93,
                    'children' => [
                        [
                            'id' => 99,
                            'type' => 98,
                        ]
                    ]
                ],
                [
                    'id' => 100,
                    'type' => 93,
                    'children' => [
                        [
                            'id' => 101,
                            'type' => 100,
                        ]
                    ]
                ],
                [
                    'id' => 102,
                    'type' => 93,
                    'children' => [
                        [
                            'id' => 103,
                            'type' => 102,
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 104,
            'type' => 104,
            'children' => [
                [
                    'id' => 105,
                    'type' => 104,
                    'children' => [
                        [
                            'id' => 106,
                            'type' => 105,
                        ]
                    ]
                ],
                [
                    'id' => 107,
                    'type' => 104,
                    'children' => [
                        [
                            'id' => 108,
                            'type' => 107,
                        ]
                    ]
                ],
                [
                    'id' => 109,
                    'type' => 104,
                    'children' => [
                        [
                            'id' => 110,
                            'type' => 109,
                        ]
                    ]
                ],
                [
                    'id' => 111,
                    'type' => 104,
                    'children' => [
                        [
                            'id' => 112,
                            'type' => 111,
                        ]
                    ]
                ],
                [
                    'id' => 113,
                    'type' => 104,
                    'children' => [
                        [
                            'id' => 114,
                            'type' => 113,
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 115,
            'type' => 115,
            'children' => [
                [
                    'id' => 116,
                    'type' => 115,
                    'children' => [
                        [
                            'id' => 117,
                            'type' => 116,
                        ],
                        [
                            'id' => 118,
                            'type' => 116,
                        ],
                        [
                            'id' => 119,
                            'type' => 116,
                        ],
                        [
                            'id' => 120,
                            'type' => 116,
                        ],
                        [
                            'id' => 121,
                            'type' => 116,
                        ],
                        [
                            'id' => 122,
                            'type' => 116,
                        ],
                        [
                            'id' => 123,
                            'type' => 116,
                        ],
                        [
                            'id' => 124,
                            'type' => 116,
                        ],
                        [
                            'id' => 125,
                            'type' => 116,
                        ],
                        [
                            'id' => 126,
                            'type' => 116,
                        ],
                        [
                            'id' => 127,
                            'type' => 116,
                        ]
                    ]
                ],
                [
                    'id' => 128,
                    'type' => 115,
                    'children' => [
                        [
                            'id' => 129,
                            'type' => 128,
                        ],
                        [
                            'id' => 130,
                            'type' => 128,
                        ],
                        [
                            'id' => 131,
                            'type' => 128,
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 132,
            'type' => 132,
            'children' => [
                [
                    'id' => 133,
                    'type' => 132,
                    'children' => [
                        [
                            'id' => 134,
                            'type' => 133,
                        ],
                        [
                            'id' => 135,
                            'type' => 133,
                        ],
                        [
                            'id' => 136,
                            'type' => 133,
                        ]
                    ]
                ],
                [
                    'id' => 137,
                    'type' => 132,
                    'children' => [
                        [
                            'id' => 138,
                            'type' => 137,
                        ],
                        [
                            'id' => 139,
                            'type' => 137,
                        ],
                        [
                            'id' => 140,
                            'type' => 137,
                        ]
                    ]
                ],
                [
                    'id' => 141,
                    'type' => 132,
                    'children' => [
                        [
                            'id' => 142,
                            'type' => 141,
                        ],
                        [
                            'id' => 143,
                            'type' => 141,
                        ],
                        [
                            'id' => 144,
                            'type' => 141,
                        ]
                    ]
                ],
                [
                    'id' => 145,
                    'type' => 132,
                    'children' => [
                        [
                            'id' => 146,
                            'type' => 145,
                        ],
                        [
                            'id' => 147,
                            'type' => 145,
                        ],
                        [
                            'id' => 148,
                            'type' => 145,
                        ]
                    ]
                ],
                [
                    'id' => 149,
                    'type' => 132,
                    'children' => [
                        [
                            'id' => 150,
                            'type' => 149,
                        ],
                        [
                            'id' => 151,
                            'type' => 149,
                        ]
                    ]
                ],
                [
                    'id' => 152,
                    'type' => 132,
                    'children' => [
                        [
                            'id' => 153,
                            'type' => 152,
                        ],
                        [
                            'id' => 154,
                            'type' => 152,
                        ],
                        [
                            'id' => 155,
                            'type' => 152,
                        ],
                        [
                            'id' => 156,
                            'type' => 152,
                        ],
                        [
                            'id' => 157,
                            'type' => 152,
                        ],
                        [
                            'id' => 158,
                            'type' => 152,
                        ],
                        [
                            'id' => 159,
                            'type' => 152,
                        ],
                        [
                            'id' => 160,
                            'type' => 152,
                        ]
                    ]
                ],
                [
                    'id' => 161,
                    'type' => 132,
                    'children' => [
                        [
                            'id' => 162,
                            'type' => 161,
                        ],
                        [
                            'id' => 163,
                            'type' => 161,
                        ],
                        [
                            'id' => 164,
                            'type' => 161,
                        ],
                        [
                            'id' => 165,
                            'type' => 161,
                        ],
                        [
                            'id' => 166,
                            'type' => 161,
                        ],
                        [
                            'id' => 167,
                            'type' => 161,
                        ],
                        [
                            'id' => 168,
                            'type' => 161,
                        ],
                        [
                            'id' => 169,
                            'type' => 161,
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 170,
            'type' => 170,
            'children' => [
                [
                    'id' => 171,
                    'type' => 170,
                    'children' => [
                        [
                            'id' => 172,
                            'type' => 171,
                        ],
                        [
                            'id' => 173,
                            'type' => 171,
                        ],
                        [
                            'id' => 174,
                            'type' => 171,
                        ],
                        [
                            'id' => 175,
                            'type' => 171,
                        ]
                    ]
                ],
                [
                    'id' => 176,
                    'type' => 170,
                    'children' => [
                        [
                            'id' => 177,
                            'type' => 176,
                        ],
                        [
                            'id' => 178,
                            'type' => 176,
                        ],
                    ]
                ],
                [
                    'id' => 179,
                    'type' => 170,
                    'children' => [
                        [
                            'id' => 180,
                            'type' => 179,
                        ],
                        [
                            'id' => 181,
                            'type' => 179,
                        ],
                        [
                            'id' => 182,
                            'type' => 179,
                        ],
                        [
                            'id' => 183,
                            'type' => 179,
                        ]
                    ]
                ],
                [
                    'id' => 184,
                    'type' => 170,
                    'children' => [
                        [
                            'id' => 185,
                            'type' => 184,
                        ],
                        [
                            'id' => 186,
                            'type' => 184,
                        ],
                        [
                            'id' => 187,
                            'type' => 184,
                        ],
                        [
                            'id' => 188,
                            'type' => 184,
                        ],
                        [
                            'id' => 189,
                            'type' => 184,
                        ]
                    ]
                ],
                [
                    'id' => 190,
                    'type' => 170,
                    'children' => [
                        [
                            'id' => 191,
                            'type' => 190,
                        ],
                        [
                            'id' => 192,
                            'type' => 190,
                        ],
                        [
                            'id' => 193,
                            'type' => 190,
                        ],
                        [
                            'id' => 194,
                            'type' => 190,
                        ]
                    ]
                ],
                [
                    'id' => 195,
                    'type' => 170,
                    'children' => [
                        [
                            'id' => 196,
                            'type' => 195,
                        ],
                        [
                            'id' => 197,
                            'type' => 195,
                        ],
                        [
                            'id' => 198,
                            'type' => 195,
                        ],
                        [
                            'id' => 199,
                            'type' => 195,
                        ],
                        [
                            'id' => 200,
                            'type' => 195,
                        ],
                        [
                            'id' => 201,
                            'type' => 195,
                        ],
                        [
                            'id' => 202,
                            'type' => 195,
                        ],
                        [
                            'id' => 203,
                            'type' => 195,
                        ]
                    ]
                ],
                [
                    'id' => 204,
                    'type' => 170,
                    'children' => [
                        [
                            'id' => 205,
                            'type' => 204,
                        ],
                        [
                            'id' => 206,
                            'type' => 204,
                        ],
                        [
                            'id' => 207,
                            'type' => 204,
                        ]
                    ]
                ],
            ]
        ],
        [
            'id' => 208,
            'type' => 208,
            'children' => [
                [
                    'id' => 209,
                    'type' => 208,
                    'children' => [
                        [
                            'id' => 210,
                            'type' => 209,
                        ],
                        [
                            'id' => 211,
                            'type' => 209,
                        ],
                        [
                            'id' => 212,
                            'type' => 209,
                        ]
                    ]
                ],
                [
                    'id' => 213,
                    'type' => 208,
                    'children' => [
                        [
                            'id' => 214,
                            'type' => 213,
                        ],
                        [
                            'id' => 215,
                            'type' => 213,
                        ]
                    ]
                ],
                [
                    'id' => 216,
                    'type' => 208,
                    'children' => [
                        [
                            'id' => 217,
                            'type' => 216,
                        ],
                        [
                            'id' => 218,
                            'type' => 216,
                        ],
                        [
                            'id' => 219,
                            'type' => 216,
                        ]
                    ]
                ],
                [
                    'id' => 220,
                    'type' => 208,
                    'children' => [
                        [
                            'id' => 221,
                            'type' => 220,
                        ],
                        [
                            'id' => 222,
                            'type' => 220,
                        ],
                        [
                            'id' => 223,
                            'type' => 220,
                        ]
                    ]
                ],
            ]
        ],
        [
            'id' => 224,
            'type' => 224,
            'children' => [
                [
                    'id' => 225,
                    'type' => 224,
                    'children' => [
                        [
                            'id' => 226,
                            'type' => 225,
                        ],
                        [
                            'id' => 227,
                            'type' => 225,
                        ],
                        [
                            'id' => 228,
                            'type' => 225,
                        ],
                        [
                            'id' => 229,
                            'type' => 225,
                        ],
                        [
                            'id' => 230,
                            'type' => 225,
                        ],
                        [
                            'id' => 231,
                            'type' => 225,
                        ],
                        [
                            'id' => 232,
                            'type' => 225,
                        ],
                        [
                            'id' => 233,
                            'type' => 225,
                        ]
                    ]
                ],
                [
                    'id' => 234,
                    'type' => 224,
                    'children' => [
                        [
                            'id' => 235,
                            'type' => 234,
                        ],
                    ]
                ],
                [
                    'id' => 236,
                    'type' => 224,
                    'children' => [
                        [
                            'id' => 237,
                            'type' => 236,
                        ],
                    ]
                ],
            ]
        ],
        [
            'id' => 238,
            'type' => 238,
            'children' => [
                [
                    'id' => 239,
                    'type' => 238,
                    'children' => [
                        [
                            'id' => 240,
                            'type' => 239,
                        ],
                        [
                            'id' => 241,
                            'type' => 239,
                        ],
                        [
                            'id' => 242,
                            'type' => 239,
                        ],
                        [
                            'id' => 243,
                            'type' => 239,
                        ],
                        [
                            'id' => 244,
                            'type' => 239,
                        ],
                        [
                            'id' => 245,
                            'type' => 239,
                        ],
                    ]
                ],
                [
                    'id' => 246,
                    'type' => 238,
                    'children' => [
                        [
                            'id' => 247,
                            'type' => 246,
                        ],
                        [
                            'id' => 248,
                            'type' => 246,
                        ],
                        [
                            'id' => 249,
                            'type' => 246,
                        ],
                        [
                            'id' => 250,
                            'type' => 246,
                        ]
                    ]
                ],
                [
                    'id' => 251,
                    'type' => 238,
                    'children' => [
                        [
                            'id' => 252,
                            'type' => 251,
                        ],
                        [
                            'id' => 253,
                            'type' => 251,
                        ],
                        [
                            'id' => 254,
                            'type' => 251,
                        ],
                        [
                            'id' => 255,
                            'type' => 251,
                        ]
                    ]
                ],
                [
                    'id' => 256,
                    'type' => 238,
                    'children' => [
                        [
                            'id' => 257,
                            'type' => 256,
                        ],
                        [
                            'id' => 258,
                            'type' => 256,
                        ],
                    ]
                ],
            ]
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->taskStart("PermissionSeeder");

        $this->execute(self::level);

        // 任務結束時間
        $this->taskEnd();
    }

    /**
     * execute
     *
     * @param array $tree
     * @param array $params
     * @param integer $featureIndex
     *
     * @return \Illuminate\Support\Collection
     */
    private function execute($tree, $params = [], $featureIndex = null)
    {
        return collect($tree)->map(fn ($item) => $this->handle($item, $params, $featureIndex));
    }

    /**
     * @param array $item
     * @param array $params
     * @param integer $featureIndex
     *
     * @return $this
     */
    protected function handle($item, $params, $featureIndex)
    {
        $item = $this->permission($item);

        return $item->has('children') ? $this->hasChildren($item, $params, $featureIndex) : $this->hasNotChildren($item, $params, $featureIndex);
    }

    /**
     * @param array $item
     *
     * @return \Illuminate\Support\Collection
     */
    public function permission($item)
    {
        $item = collect($item);

        Permission::create([
            'guard_name' => 'user',
            'name' => $item->get('type')
        ]);

        return $item;
    }

    /**
     * @param \Illuminate\Support\Collection $item
     * @param array $params
     * @param integer $featureIndex
     *
     * @return $this
     */
    protected function hasChildren($item, $params = [], $featureIndex)
    {
        $data = ['permission_id' => $item->get('id')];

        $permissionLevel = PermissionLevel::find($item->get('id'));
        if (!$permissionLevel)
            $data['parent_id'] = $featureIndex;

        $this->permissionLevel($data, empty($params) ? null : $params);

        $this->rolePerimssion((int) $item->get('type'), (int) $item->get('id'));
        $this->execute($item->get('children'), $permissionLevel, $item->get('id'));

        return $this;
    }

    /**
     * @param \Illuminate\Support\Collection $item
     * @param array $params
     * @param integer $featureIndex
     *
     * @return $this
     */
    protected function hasNotChildren($item, $params = [], $featureIndex)
    {
        $this->rolePerimssion((int) $item->get('type'), (int) $item->get('id'));

        $this->permissionLevel([
            'permission_id' => $item->get('id'),
            'parent_id' => $featureIndex
        ], $params);

        return $this;
    }

    /**
     * @param integer $type
     * @param integer $id
     *
     * @return $this
     */
    private function rolePerimssion($id)
    {
        return $this->mainRole($id);
    }

    /**
     * @param integer $index
     *
     * @return $this
     */
    private function admin($index)
    {
        switch ($index) {
            case 25:
            case 27:
            case 35:
            case 38:
            case 48:
                return false;
                break;
        }

        return $this->giveRolePermission('admin', $index);
    }

    private function owner($index)
    {
        switch ($index) {
            case 25:
            case 27:
            case 35:
            case 38:
            case 48:
            case 72:
            case 74:
            case 178:
            case 179:
            case 180:
            case 184:
            case 185:
            case 186:
                return false;
                break;
        }

        return $this->giveRolePermission('owner', $index);
    }

    // private function owner($index)
    // {
    //     switch ($index) {
    //         case :
    //         break;
    //     }

    //     return $this->giveRolePermission('', $index);
    // }

    // private function owner($index)
    // {
    //     switch ($index) {
    //         case :
    //         break;
    //     }

    //     return $this->giveRolePermission('', $index);
    // }

    // private function owner($index)
    // {
    //     switch ($index) {
    //         case :
    //         break;
    //     }

    //     return $this->giveRolePermission('', $index);
    // }

    /**
     * main role
     *
     * @param string|integer $index
     *
     * @return $this
     */
    public function mainRole($index)
    {
        $this->admin($index);
        // Role::findByName('agent', 'user')->givePermissionTo($index);
        // Role::findByName('owner', 'user')->givePermissionTo($index);
        // Role::findByName('customer-service-supervisor', 'user')->givePermissionTo($index);
        // Role::findByName('agent_sub', 'user')->givePermissionTo($index);

        return $this;
    }
}
