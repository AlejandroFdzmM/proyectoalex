resource "aws_security_group" "alb_sg" {
  name        = "project-alb-sg"
  description = "security group lb"
  vpc_id      = aws_vpc.terraform.id

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port         = 80
    to_port           = 80
    protocol          = "tcp"
    ipv6_cidr_blocks  = ["::/0"]
  }

  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port         = 443
    to_port           = 443
    protocol          = "tcp"
    ipv6_cidr_blocks  = ["::/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port         = 0
    to_port           = 0
    protocol          = "-1"
    ipv6_cidr_blocks  = ["::/0"]
  }

  tags = {
    Name = "project-alb-sg"
  }
}

resource "aws_security_group" "eks_sg" {
  name        = "project-eks-sg"
  description = "security group eks"
  vpc_id      = aws_vpc.terraform.id

  tags = {
    Name = "project-eks-sg"
  }
}
