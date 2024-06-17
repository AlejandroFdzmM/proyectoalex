resource "null_resource" "kubeconfig" {
  provisioner "local-exec" {
    command = "aws eks update-kubeconfig --region us-east-1 --name ${aws_eks_cluster.proyecto.name}"
  }

  depends_on = [aws_eks_node_group.proyecto]
}

output "kubeconfig_command" {
  value = "aws eks update-kubeconfig --region us-east-1 --name ${aws_eks_cluster.proyecto.name}"
}




resource "null_resource" "kubectl" {
  provisioner "local-exec" {
     command = "kubectl apply -f C:/terrafo/final/prueba12"
  }
  depends_on = [null_resource.kubeconfig]
}
output "kubectl_command" {
  value = "kubectl apply -f C:/terrafo/final/prueba12"
}


resource "null_resource" "destroy" {
  provisioner "local-exec" {
     when = destroy
     command = "kubectl delete -f C:/terrafo/final/prueba12"
  }

}
