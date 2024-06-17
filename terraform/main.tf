/**  VPC de AWS **/

resource "aws_vpc" "terraform" {
  cidr_block       = "10.0.0.0/16"
  instance_tenancy = "default"
  assign_generated_ipv6_cidr_block = true

  tags = {
    Name = "VPC-Terraform"
  }
}

/** Subred publica de la primera zona **/
resource "aws_subnet" "terraform" {
  vpc_id     = aws_vpc.terraform.id
  cidr_block = "10.0.0.0/24"
  availability_zone = "us-east-1a"

  tags = {
    Name = "Terraform-Publica"
  }
}

/** Subred privada de la primera zona **/
resource "aws_subnet" "terraform2" {
  vpc_id     = aws_vpc.terraform.id
  cidr_block = "10.0.2.0/23"
  availability_zone = "us-east-1a"
  tags = {
    Name = "Terraform-Privada"
  }
}

/** Subred publica de la primera zona **/
resource "aws_subnet" "terraformcopia" {
  vpc_id     = aws_vpc.terraform.id
  cidr_block = "10.0.1.0/24"
  availability_zone = "us-east-1b"

  tags = {
    Name = "Terraform-Publicacopia"
  }
}

/** Subred privada de la segunda zona **/
resource "aws_subnet" "terraform2copia" {
  vpc_id     = aws_vpc.terraform.id
  cidr_block = "10.0.4.0/23"
  availability_zone = "us-east-1b"
  tags = {
    Name = "Terraform-Privadacopia"
  }
}



/** IP elastica **/
resource "aws_eip" "lb" {
  domain   = "vpc"
}

/** Puerta de enlace de subred privada, primera zona **/
resource "aws_nat_gateway" "terraform" {
  allocation_id = aws_eip.lb.id
  subnet_id     = aws_subnet.terraform.id

  tags = {
    Name = "gw NAT terraform"
  }
}

/** Tabla enrutamiento de subred privada, primera zona **/
resource "aws_route_table" "terraform2" {
  vpc_id = aws_vpc.terraform.id

  route {
    cidr_block = "10.0.0.0/16"
    gateway_id = "local"
  }

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_nat_gateway.terraform.id
  }

  tags = {
    Name = "Terraform-privada"
  }
}

/** Tabla de enrutamiento de la subred publica, primera zona **/ 
resource "aws_route_table" "terraform" {
  vpc_id = aws_vpc.terraform.id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.igw.id
  }

  route {
    ipv6_cidr_block        = "::/0"
    gateway_id = aws_internet_gateway.igw.id
  }

  route {
    cidr_block = "10.0.0.0/16"
    gateway_id = "local"
  }

  tags = {
    Name = "Terraform-public"
  }
}

/*** Asociacion de las tablas de enrutamiento subred privada y publica, primera zona **/
resource "aws_route_table_association" "a" {
  subnet_id      = aws_subnet.terraform.id
  route_table_id = aws_route_table.terraform.id
}

resource "aws_route_table_association" "b" {
  subnet_id      = aws_subnet.terraform2.id
  route_table_id = aws_route_table.terraform2.id
}

/**  Ip elastica para la segunda zona **/
resource "aws_eip" "lbcopia" {
  domain   = "vpc"
}

/** Puerta de enlace de subred privada, segunda zona **/
resource "aws_nat_gateway" "terraformcopia" {
  allocation_id = aws_eip.lbcopia.id
  subnet_id     = aws_subnet.terraformcopia.id

  tags = {
    Name = "gw NAT terraform copia"
  }
}

/** Tabla de enrutamiento de la subred privada, segunda zona **/
resource "aws_route_table" "terraformcopiaprivada" {
  vpc_id = aws_vpc.terraform.id

  route {
    cidr_block = "10.0.0.0/16"
    gateway_id = "local"
  }

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_nat_gateway.terraformcopia.id
  }

  tags = {
    Name = "Terraform-privada copia"
  }
}

/** Tabla de enrutamiento de la subred publica, segunda zona **/
resource "aws_route_table" "terraformcopiapublica" {
  vpc_id = aws_vpc.terraform.id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.igw.id
  }

  route {
    ipv6_cidr_block        = "::/0"
    gateway_id = aws_internet_gateway.igw.id
  }

  route {
    cidr_block = "10.0.0.0/16"
    gateway_id = "local"
  }

  tags = {
    Name = "Terraform-public-copia"
  }
}



/*** Asociacion de las tablas de enrutamiento subred privada y publica, primera zona **/

resource "aws_route_table_association" "copiaa" {
  subnet_id      = aws_subnet.terraformcopia.id
  route_table_id = aws_route_table.terraformcopiapublica.id
}

resource "aws_route_table_association" "copiab" {
  subnet_id      = aws_subnet.terraform2copia.id
  route_table_id = aws_route_table.terraformcopiaprivada.id
}



/** Puerta de enlace de la VPC **/
resource "aws_internet_gateway" "igw" {
  vpc_id = aws_vpc.terraform.id

  tags = {
    Name = "Internet Gateway"
  }
}
