<?php
return [
    '2.0.0' => [
        'date' => '2030-03-15',
        'items' => [
            [
                'type' => 'new',
                'description' => 'Implementado sistema de notificações SMS para alertar clientes quando faltam 3 posições para serem atendidos'
            ],
            [
                'type' => 'new',
                'description' => 'Adicionado dashboard analítico com métricas de tempo médio de espera e taxa de abandono'
            ],
            [
                'type' => 'refactor',
                'description' => 'Otimizado o algoritmo de ordenação de filas, reduzindo tempo de processamento em 40%'
            ],
            [
                'type' => 'fix',
                'description' => 'Corrigido bug que causava duplicação de tickets quando múltiplos utilizadores acediam simultaneamente'
            ],
            [
                'type' => 'security',
                'description' => 'Implementada autenticação de dois fatores para acesso ao painel administrativo'
            ]
        ]
    ],

    '2.0.1' => [
        'date' => '2030-05-10',
        'items' => [
            [
                'type' => 'fix',
                'description' => 'Resolvido problema de exibição incorreta do número de pessoas na fila em dispositivos móveis'
            ],
            [
                'type' => 'fix',
                'description' => 'Corrigida falha no sistema de prioridades que não respeitava atendimento preferencial'
            ],
            [
                'type' => 'refactor',
                'description' => 'Melhorada a estrutura de base de dados para suportar múltiplas filas por serviço'
            ],
            [
                'type' => 'new',
                'description' => 'Adicionada funcionalidade de pausa temporária de atendimento com notificação automática aos clientes'
            ]
        ]
    ],

    '2.0.2' => [
        'date' => '2030-08-22',
        'items' => [
            [
                'type' => 'new',
                'description' => 'Lançamento da versão 2.0 com interface completamente redesenhada'
            ],
            [
                'type' => 'new',
                'description' => 'Implementado sistema de QR Code para entrada automática na fila via smartphone'
            ],
            [
                'type' => 'new',
                'description' => 'Adicionado suporte para múltiplos idiomas (PT, EN, ES, FR)'
            ],
            [
                'type' => 'refactor',
                'description' => 'Migração completa para Laravel 10 e PHP 8.2'
            ],
            [
                'type' => 'security',
                'description' => 'Implementado sistema de logs de auditoria para todas as ações administrativas'
            ],
            [
                'type' => 'fix',
                'description' => 'Corrigidos diversos problemas de sincronização em tempo real usando Laravel Echo'
            ]
        ]
    ],

    '2.0.3' => [
        'date' => '2030-10-05',
        'items' => [
            [
                'type' => 'fix',
                'description' => 'Resolvido erro crítico que impedia a chamada de próximo cliente em horários de pico'
            ],
            [
                'type' => 'fix',
                'description' => 'Corrigida exportação de relatórios em formato Excel'
            ],
            [
                'type' => 'security',
                'description' => 'Atualização de dependências de segurança e patches do Laravel'
            ],
            [
                'type' => 'refactor',
                'description' => 'Otimizado carregamento de histórico de atendimentos com lazy loading'
            ]
        ]
    ]
];
