CREATE DATABASE IF NOT EXISTS `biblioteca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `biblioteca`;

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int(11) NOT NULL,
  `nome` varchar(90) NOT NULL,
  `serie` varchar(12) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aluno`
--

INSERT INTO `aluno` (`id`, `nome`, `serie`, `email`) VALUES
(1, 'Aluno', '1º Ano EM A', 'aluno@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `anotacoes`
--

CREATE TABLE `anotacoes` (
  `id` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `texto` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `anotacoes`
--

INSERT INTO `anotacoes` (`id`, `id_professor`, `texto`, `data`) VALUES
(1, 1, 'jogo', '2025-08-21 15:19:40');

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimo`
--

CREATE TABLE `emprestimo` (
  `id` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `data_emprestimo` date NOT NULL,
  `data_devolucao` date NOT NULL,
  `status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro`
--

CREATE TABLE `livro` (
  `id` int(11) NOT NULL,
  `nome_livro` varchar(190) NOT NULL,
  `nome_autor` varchar(130) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livro`
--

INSERT INTO `livro` (`id`, `nome_livro`, `nome_autor`, `isbn`) VALUES
(1, 'Memórias da Rua do Ouvidor', 'Joaquim Manuel de Macedo', ''),
(2, 'trev', 'zcdasc', ''),
(3, 'Curso de Direito Penal Brasileiro - Parte Geral', 'Luiz Regis Prado', '9786559596775'),
(4, 'The Career and Legend of Vasco Da Gama', 'Sanjay Subrahmanyam', '0521646294');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `id` int(11) NOT NULL,
  `nome` varchar(90) NOT NULL,
  `cpf` char(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`id`, `nome`, `cpf`, `email`, `senha`) VALUES
(1, 'Professor', '00000000000', 'professor@email.com', '$2y$10$9wsKRk73Ak7JUVY88kKfM.fXP1c5t9aMP/o2J3IxJ/AsaVrCEpjZq');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `anotacoes`
--
ALTER TABLE `anotacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_anotacao_professor` (`id_professor`);

--
-- Índices de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_emprestimo_aluno` (`id_aluno`),
  ADD KEY `fk_emprestimo_professor` (`id_professor`),
  ADD KEY `fk_emprestimo_livro` (`id_livro`);

--
-- Índices de tabela `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `anotacoes`
--
ALTER TABLE `anotacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `anotacoes`
--
ALTER TABLE `anotacoes`
  ADD CONSTRAINT `fk_anotacao_professor` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD CONSTRAINT `fk_emprestimo_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emprestimo_livro` FOREIGN KEY (`id_livro`) REFERENCES `livro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emprestimo_professor` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;