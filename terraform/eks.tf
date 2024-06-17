/** EKS CLUSTER **/
resource "aws_eks_cluster" "proyecto" {
  name     = "proyecto"
  role_arn = "arn:aws:iam::440294365376:role/LabRole"

  

  vpc_config {
    subnet_ids = [
      aws_subnet.terraform.id,
      aws_subnet.terraform2.id,
      aws_subnet.terraform2copia.id,
      aws_subnet.terraformcopia.id
    ]
    endpoint_public_access = true
    endpoint_private_access = true
  }

}


/** Node group **/
resource "aws_eks_node_group" "proyecto" {
  cluster_name    = aws_eks_cluster.proyecto.name
  node_group_name = "proyecto2"
  node_role_arn   = "arn:aws:iam::440294365376:role/LabRole"
  /**instance_types = ["t2.medium"] por defecto, puede modificarse  **/
  
  subnet_ids      = [
    aws_subnet.terraform2copia.id,
    aws_subnet.terraform2.id
    ]

  scaling_config {
    desired_size = 2
    max_size     = 4
    min_size     = 2
  }

  update_config {
    max_unavailable = 1
  }

}


